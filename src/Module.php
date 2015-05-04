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

/**
 * Module class of the enhanced captcha functionality with the default params 
 * loaded.
 * The module MUST be set in the modules section of your application file and 
 * MUST be bootstraped or MUST be loaded in the action that uses it.
 * 
 * .../myappconfig.php ->
 * [
 *     ...
 *     'bootstrap' => [
 *         ...
 *         , 'captcha'
 *     ],
 *     'modules' => [
 *         ...
 *         , 'captcha' => [
 *             'class' => 'jlorente\captcha\Module',
 *             'cache' => [
 *                 'class' => 'yii\caching\ApcCache',
 *                 'keyPrefix' => 'captcha_'
 *             ]
 *         ]
 *     ]
 * ]
 * 
 * 
 * @author José Lorente <jose.lorente.martin@gmail.com>
 */
class Module extends BaseModule {

    /**
     *
     * @var int Request number allowed before the captcha is shown.
     */
    public $requestNumber = 2;
    
    /**
     *
     * @var int Duration of the time period to check in miliseconds
     */
    public $duration = 60;

    /**
     *
     * @var array Params to create the cache component
     * 
     * i.e.:
     * [
     *     ...
     *     'modules' => [
     *         ...
     *         'captcha' => [
     *             'class' => 'jlorente\captcha\Module',
     *             'cache' => [
     *                 'class' => 'yii\caching\ApcCache',
     *                 'keyPrefix' => 'captcha_'
     *             ]
     *         ]
     *     ]
     * ]
     */
    public $cache;
    
    /**
     * @inheritdoc
     * 
     * Creates the default cache component if it is not provided.
     */
    public function init() {
        parent::init();

        if ($this->cache === null) {
            $this->cache = [
                'class' => ApcCache::className(),
                'keyPrefix' => 'captcha_'
            ];
        }

        $this->setComponents([
            'cache' => $this->cache,
        ]);
    }

}
