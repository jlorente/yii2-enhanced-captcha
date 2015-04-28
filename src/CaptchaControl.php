<?php

/**
 * @author      José Lorente <jose.lorente.martin@gmail.com>
 * @copyright   Gabinete Jurídico y de Transportes <https://portalabogados.es>
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
            $this->cacheKey = $this->getModule()->apc->buildKey(Yii::$app->request->userIp . '_' . get_class($this->model));
        }
        return $this->cacheKey;
    }

    public function getRequestNumber() {
        return count($this->getQueue());
    }

    public function hit() {
        $this->refreshCache();
        $queue = $this->getQueue();
        $queue->enqueue(time());
        $this->setInternal($queue);
    }

    public function hasReachedRequestNumber() {
        return $this->getRequestNumber() > $this->getModule()->requestNumber;
    }
    
    protected function getQueue() {
        $cacheKey = $this->getCacheKey();
        $now = time();
        if ($this->getModule()->apc->exists($cacheKey)) {
            $this->queue = $this->getModule()->apc->get($cacheKey);
            $modified = false;
            $expired = $now - $this->getModule()->duration;
            while ($this->queue->top() < $expired) {
                $this->queue->dequeue();
                $modified = true;
            }
            if ($modified) {
                $this->setInternal($this->queue);
            }
        } else {
            $this->queue = new SplQueue();
        }
    }

    protected function setInternal(SplQueue $queue) {
        $this->getModule()->apc->set($this->getCacheKey(), $queue, $this->getModule()->duration);
    }
}
