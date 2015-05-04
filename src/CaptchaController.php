<?php

/**
 * @author      José Lorente <jose.lorente.martin@gmail.com>
 * @license     The MIT License (MIT)
 * @copyright   José Lorente
 * @version     1.0
 */

namespace jlorente\captcha;

use yii\web\Controller;

/**
 * CaptchaController provides the action that renders and provides the CAPTCHA 
 * code.
 * 
 * @author José Lorente <jose.lorente.martin@gmail.com>
 */
class CaptchaController extends Controller {

    /**
     * @inheritdoc
     * 
     * Configuration array to create the action is provided in the Module class.
     * 
     * @return array
     */
    public function actions() {
        return [
            'index' => Module::getInstance()->captchaAction
        ];
    }

}
