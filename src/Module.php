<?php

/**
 * @author      José Lorente <jose.lorente.martin@gmail.com>
 * @license     The MIT License (MIT)
 * @copyright   José Lorente
 * @version     1.0
 */

namespace jlorente\captcha;

use yii\base\Module as BaseModule;
use yii\caching\ApcCache;

class Module extends BaseModule {

    public $requestNumber = 2;
    
    public $duration = 60;
    
    public function init() {
        parent::init();

        $this->setAliases([
            '@jlorenteCaptcha' => '@vendor/jlorente/yii2-enhanced-captcha'
        ]);
        $this->setComponents([
            'cache' => [
                'class' => ApcCache::className()
            ],
        ]);
    }
}
