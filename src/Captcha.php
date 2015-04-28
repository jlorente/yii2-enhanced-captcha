<?php

/**
 * @author      José Lorente <jose.lorente.martin@gmail.com>
 * @license     The MIT License (MIT)
 * @copyright   José Lorente
 * @version     1.0
 */

namespace jlorente\captcha;

use yii\captcha\Captcha as BaseCaptcha;
use jlorente\captcha\Module;
use yii\base\InvalidConfigException;

class Captcha extends BaseCaptcha {

    /**
     * @inheritdoc
     */
    public function init() {
        if ($this->model === null) {
            throw new InvalidConfigException('This functionality works only with inputs created from models');
        }
        if (($this->model instanceof Captchable) === false) {
            throw new InvalidConfigException('The provided model must implement jlorente\captcha\Captchable interface');
        }
        
        $this->captchaAction = Module::getInstance()->urlManager->createUrl(['jlorente/captcha/get']);
        parent::init();
    }

    /**
     * @inheritdoc
     * 
     * Takes the decision of rendering the widget or not depending on the number 
     * of requests made from the client IP.
     */
    public function run() {
        $captchaControl = new CaptchaControl(['model' => $this->model]);
        if ($captchaControl->hasReachedRequestNumber()) {
            parent::run();
        }
    }
}
