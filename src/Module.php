<?php

/**
 * @author      José Lorente <jose.lorente.martin@gmail.com>
 * @license     The MIT License (MIT)
 * @copyright   José Lorente
 * @version     1.0
 */

namespace jlorente\captcha;

use yii\base\Module as BaseModule;

class Module extends BaseModule {

    public $controllerNamespace = 'jlorente\captcha';

    public $requestNumber = 3;
    
    public $duration = 120;
    
    public function init() {
        parent::init();

        $this->setAliases([
            '@jlorenteCaptcha' => '@vendor/jlorente/yii2-enhanced-captcha'
        ]);
        $this->setComponents([
            'urlManager' => [
                'class' => 'yii\web\UrlManager',
                'enablePrettyUrl' => true,
                'showScriptName' => false,
                'enableStrictParsing' => false,
                'rules' => ['jlorente/captcha/get']
            ],
            'apc' => [
                'class' => 'yii\caching\ApcCache'
            ],
        ]);
    }
}
