<?php

/**
 * @author      José Lorente <jose.lorente.martin@gmail.com>
 * @license     The MIT License (MIT)
 * @copyright   José Lorente
 * @version     1.0
 */

namespace jlorente\captcha;

use yii\captcha\Captcha as BaseCaptcha;
use yii\base\InvalidConfigException;

/**
 * Captcha widget extends the functionality of yii\captcha\Captcha widget class 
 * in order to decide whether to show the captcha image and field or not 
 * depending on the number of calls to the current Model::validate() method from 
 * the same IP in a time period.
 * 
 * This widget has no sense without a model, so it will throw a 
 * yii\base\InvalidConfigException if the model is not provided in the creation 
 * phase.
 * 
 * @author José Lorente <jose.lorente.martin@gmail.com>
 */
class Captcha extends BaseCaptcha {

    /**
     * @inheritdoc
     */
    public function init() {
        if ($this->model === null) {
            throw new InvalidConfigException('This functionality works only with inputs created from models');
        }

        $this->captchaAction = ['/site/captcha'];
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
