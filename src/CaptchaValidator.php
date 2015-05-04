<?php

/**
 * @author      José Lorente <jose.lorente.martin@gmail.com>
 * @license     The MIT License (MIT)
 * @copyright   José Lorente
 * @version     1.0
 */

namespace jlorente\captcha;

use yii\captcha\CaptchaValidator as BaseCaptchaValidator;
use Yii;

/**
 * CaptchaValidator extends the functionality of yii\captcha\CaptchaValidator 
 * in order to decide whether to validate the captcha attribute or not 
 * depending on the number of calls to the current Model::validate() method from 
 * the same IP in a time period.
 * 
 * @author José Lorente <jose.lorente.martin@gmail.com>
 */
class CaptchaValidator extends BaseCaptchaValidator {

    /**
     * @inheritdoc
     */
    public function init() {
        $this->captchaAction = Yii::getAlias('@captchaRoute');

        parent::init();
    }

    /**
     * @inheritdoc
     * 
     * Checks the CaptchaControl object in order to know if validation is needed 
     * or not.
     * Performs a hit in the captcha control cache component.
     */
    public function validateAttribute($model, $attribute) {
        $captchaControl = new CaptchaControl(['model' => $model]);
        if ($captchaControl->hasReachedRequestNumber()) {
            parent::validateAttribute($model, $attribute);
        }
        $captchaControl->hit();
    }

}
