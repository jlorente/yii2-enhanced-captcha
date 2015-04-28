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

class CaptchaControl extends Model {

    const CACHE_PREFIX = 'captcha_';

    protected $model;
    protected $cacheKey;
    protected $module;

    public function init() {
        parent::init();
        if ($this->model === null) {
            throw new InvalidConfigException('Model must be provided on object creation');
        }
    }

    public function setModel(Model $model) {
        $this->model = $model;
    }

    public function getModule() {
        if ($this->module === null) {
            $this->module = Module::getInstance();
        }
        return $this->module;
    }

    public function getCacheKey() {
        if ($this->cacheKey === null) {
            $this->cacheKey = $this->getModule()->cache->buildKey(Yii::$app->request->userIp . '_' . get_class($this->model));
        }
        return $this->cacheKey;
    }

    public function getRequestNumber() {
        return count($this->getQueue());
    }

    public function hit() {
        $queue = $this->getQueue();
        if (count($this->getQueue()) >= $this->getModule()->requestNumber) {
            $queue->dequeue();
        }
        $queue->enqueue(time());
        $this->setInternal($queue);
    }

    public function hasReachedRequestNumber() {
        return $this->getRequestNumber() > $this->getModule()->requestNumber;
    }

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
                $this->setInternal($queue);
            }
        }
        return $queue;
    }

    protected function setInternal(SplQueue $queue) {
        $this->getModule()->cache->set($this->getCacheKey(), $queue, $this->getModule()->duration);
    }

}
