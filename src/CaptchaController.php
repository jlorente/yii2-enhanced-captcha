<?php

/**
 * @author      José Lorente <jose.lorente.martin@gmail.com>
 * @license     The MIT License (MIT)
 * @copyright   José Lorente
 * @version     1.0
 */

namespace jlorente\captcha;

use yii\web\Controller;
use yii\captcha\CaptchaAction;

class CaptchaController extends Controller {

    public function actions() {
        return [
            'get' => [
                'class' => CaptchaAction::className()
            ]
        ];
    }

}
