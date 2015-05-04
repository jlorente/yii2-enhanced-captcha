<?php

/**
 * @author      José Lorente <jose.lorente.martin@gmail.com>
 * @license     The MIT License (MIT)
 * @copyright   José Lorente
 * @version     1.0
 */

namespace jlorente\captcha;

use SplQueue;
use Yii;
use yii\base\Model;
use yii\base\InvalidConfigException;

/**
 * CaptchaControl class is responsible of the logic to control the requests 
 * number made by the same IP to a same Model and can be accessed to obtain 
 * information about this number. 
 * The logic is implemented with a queue of timestamps nodes and optimized to 
 * avoid space wasting and time process.
 * 
 * @author José Lorente <jose.lorente.martin@gmail.com>
 */
class CaptchaControl extends Model {

    /**
     *
     * @var Model The model provided with the enhanced captcha functionality.
     */
    protected $model;

    /**
     *
     * @var string The key used to store the times queue in the cache based on the 
     * prefix key, the current request IP and the class model.
     */
    protected $cacheKey;

    /**
     * @inheritdoc
     * 
     * Model MUST be provided on object creation.
     * 
     * @throws InvalidConfigException
     */
    public function init() {
        parent::init();
        if ($this->model === null) {
            throw new InvalidConfigException('Model must be provided on object creation');
        }
    }

    /**
     * Sets the write only property model.
     * 
     * @param Model $model
     */
    public function setModel(Model $model) {
        $this->model = $model;
    }

    /**
     * Gets the captcha module.
     * 
     * @return Module
     */
    public function getModule() {
        return Module::getInstance();
    }

    /**
     * Gets the key used to store the times queue in the cache based on the 
     * prefix key, the current request IP and the class model.
     * 
     * @return string
     */
    public function getCacheKey() {
        if ($this->cacheKey === null) {
            $module = $this->getModule();
            $this->cacheKey = $module->cache->buildKey(Yii::$app->request->userIp . '_' . get_class($this->model));
        }
        return $this->cacheKey;
    }

    /**
     * Creates a new node of the queue with the current time and enqueues it.
     * In order to avoid wasting space, the queue is never allowed to have more 
     * nodes that the ones stablished in the module param requestNumber. So, when 
     * a new node is added and the queue reach the requests number, the node 
     * in the top of the queue is despised.
     */
    public function hit() {
        $queue = $this->getQueue();
        if (count($this->getQueue()) >= $this->getModule()->requestNumber) {
            $queue->dequeue();
        }
        $queue->enqueue(time());
        $this->persist($queue);
    }

    /**
     * Gets the number of requests in the current time period.
     * 
     * @return int
     */
    public function getRequestNumber() {
        return count($this->getQueue());
    }

    /**
     * Checks whether the user has reached the number of requests allowed 
     * before the captcha must be shown or not.
     * 
     * @return bool
     */
    public function hasReachedRequestNumber() {
        return $this->getRequestNumber() >= $this->getModule()->requestNumber;
    }

    /**
     * Gets the current times queue and updates the one stored in the cache
     * if necessary.
     * 
     * @return SplQueue
     */
    protected function getQueue() {
        $cacheKey = $this->getCacheKey();
        $now = time();
        $queue = new SplQueue();
        if ($this->getModule()->cache->exists($cacheKey)) {
            $queue = $this->getModule()->cache->get($cacheKey);
            $modified = false;
            $expired = $now - $this->getModule()->duration;
            while ($queue->top() < $expired) {
                $queue->dequeue();
                $modified = true;
            }
            if ($modified) {
                $this->persist($queue);
            }
        }
        return $queue;
    }

    /**
     * Persists the queue in the cache with the duration stablished in the module.
     * 
     * @param SplQueue $queue
     */
    protected function persist(SplQueue $queue) {
        $this->getModule()->cache->set($this->getCacheKey(), $queue, $this->getModule()->duration);
    }

}
