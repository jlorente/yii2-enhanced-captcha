<?php

/**
 * @author      José Lorente <jose.lorente.martin@gmail.com>
 * @license     The MIT License (MIT)
 * @copyright   José Lorente
 * @version     1.0
 */

namespace jlorente\captcha;

use yii\captcha\CaptchaValidator as BaseCaptchaValidator;

class CaptchaValidator extends BaseCaptchaValidator {

    /**
     * @inheritdoc
     * 
     * Checks the CaptchaControl object in order to know if validation is needed 
     * or not.
     * Performs a hit for the model after validation.
     */
    public function validateAttribute($model, $attribute) {
        $captchaControl = new CaptchaControl(['model' => $model]);
        if ($captchaControl->hasReachedRequestNumber()) {
            parent::validateAttribute($model, $attribute);
        }
        $captchaControl->hit();
    }

}
