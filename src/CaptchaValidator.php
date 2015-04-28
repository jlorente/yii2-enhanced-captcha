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

    public function validateAttribute($model, $attribute) {
        $captchaControl = new CaptchaControl(['model' => $model]);
        if ($captchaControl->hasReachedRequestNumber()) {
            parent::validateAttribute($model, $attribute);
        }
    }

}
