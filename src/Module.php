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
use yii\base\BootstrapInterface;
use yii\captcha\CaptchaAction;
use Yii;

/**
 * Module class of the enhanced captcha functionality with the default params 
 * loaded.
 * The module MUST be set in the modules section of your application file and 
 * MUST be bootstraped or MUST be loaded before using it.
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
 * @author José Lorente <jose.lorente.martin@gmail.com>
 */
class Module extends BaseModule implements BootstrapInterface {

    /**
     *
     * @var int Request number allowed before the captcha is shown.
     */
    public $requestNumber = 2;

    /**
     *
     * @var int Duration of the time period to check in miliseconds.
     */
    public $duration = 60;

    /**
     *
     * @var array Configuration array to create the CaptchaAction object.
     */
    public $captchaAction;

    /**
     *
     * @var array Configuration array to create the Cache object.
     */
    protected $cache;
    
    /**
     * @inheritdoc
     * 
     * Creates the default cache component if it is not provided.
     */
    public function init() {
        parent::init();

        $this->controllerNamespace = 'jlorente\captcha';

        $this->setAliases([
            '@captchaRoute' => '/' . $this->getUniqueId() . '/captcha/index',
        ]);

        if (empty($this->cache)) {
            $this->setCache([
                'class' => ApcCache::className(),
                'keyPrefix' => 'captcha_'
            ]);
        }

        if (empty($this->captchaAction)) {
            $this->captchaAction = [
                'class' => CaptchaAction::className(),
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null
            ];
        }
    }

    /**
     * @inheritdoc
     * 
     * @param \yii\base\Module $app
     */
    public function bootstrap($app) {
        $app->getUrlManager()->addRules([
            'jlorente-captcha' => Yii::getAlias('@captchaRoute')
        ]);
    }

    /**
     * Creates the cache component with the given arguments.
     * 
     * @param array $cache
     */
    protected function setCache($cache) {
        $this->cache = $cache;
        $this->setComponents([
            'cache' => $cache,
        ]);
    }

}
