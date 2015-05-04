Yii2 Enhanced Captcha
=====================

A Yii2 extension that enhances the captcha component with the functionality of 
being shown only on multiple requests from the same IP

## Installation

Include the package as dependency under the bower.json file.

To install, either run

```bash
$ php composer.phar require jlorente/yii2-enhanced-captcha "*"
```

or add

```json
...
    "require": {
        // ... other configurations ...
        "jlorente/yii2-enhanced-captcha": "*"
    }
```

to the ```require``` section of your `composer.json` file.

## Usage

###Loading the module
First at all you have to include the plugin as a module in your config file and 
bootstrap it.

```php
<?php
//.../config/main.php
return [
    //other properties
    'modules' => [
        // list of modules,
        'captcha' => [
            'class' => 'jlorente\captcha\Module',
            //other properties initialization
        ]
    ],
    'bootstrap' => [
        //other modules to bootstrap,
        'captcha'
    ]
];
```
You can include it by other ways. Anyway, the module id is irrelevant to use 
this Module, so you can establish the one you want. For more information about 
including modules see [The Definitive Guide to Yii 2.0 - Modules](http://www.yiiframework.com/doc-2.0/guide-structure-modules.html).

The captcha Module uses a cache component to store the timestamp requests queue. 
By default it uses Apc Cache, but you can change this behavior setting the cache 
property in the module declaration.

```php
<?php
//.../config/main.php
return [
    // ... other configurations ...
    'modules' => [
        // list of modules,
        'captcha' => [
            'class' => 'jlorente\captcha\Module',
            'cache' => [
                'class' => 'yii\caching\ApcCache',
                // ... other configurations for the cache component ...
            ]
            // ... other configurations for the module ...
        ]
    ],
    'bootstrap' => [
        //other modules to bootstrap,
        'captcha'
    ]
];
```

For more information about supported cache storages and initialization of the 
component see the [manual](http://www.yiiframework.com/doc-2.0/guide-caching-data.html#supported-cache-storage).

Other properties like the duration of the time period to check and the number of 
requests before the captcha will be shown can be established in the module 
configuration too.


```php
<?php
//.../config/main.php
return [
    // ... other configurations ...
    'modules' => [
        // list of modules,
        'captcha' => [
            'class' => 'jlorente\captcha\Module',
            'cache' => [
                'class' => 'yii\caching\ApcCache',
                // ... other configurations for the cache component ...
            ],
            'duration' => 100, //In seconds
            'requestNumber' => 3
            // ... other configurations for the module ...
        ]
    ],
    'bootstrap' => [
        //other modules to bootstrap,
        'captcha'
    ]
];
```

By default the number of requests are 2 and the duration 120.

A controller action is provided along with the Module. This CaptchaAction can 
be configured in the module configuration params. See the [manual](http://www.yiiframework.com/doc-2.0/yii-captcha-captchaaction.html) 
to have a complete list of CaptchaAction configuration params.

```php
<?php
//.../config/main.php
return [
    // ... other configurations ...
    'modules' => [
        // list of modules,
        'captcha' => [
            'class' => 'jlorente\captcha\Module',
            'cache' => [
                'class' => 'yii\caching\ApcCache',
                // ... other configurations for the cache component ...
            ],
            'duration' => 100, //In seconds
            'requestNumber' => 3,
            'captchaAction' => [
                'class' => CaptchaAction::className(),
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
                // ... other configurations for the captcha action ...
            ]
            // ... other configurations for the module ...
        ]
    ],
    'bootstrap' => [
        //other modules to bootstrap,
        'captcha'
    ]
];
```


###Using the widget and the validator

Once the module is configured and loaded you can use the widget and validator 
with a Model.

Include the CaptchaValidator class as a validator for the captcha attribute.

```php
<?php
//.../models/MyModel.php
use jlorente\captcha\CaptchaValidator;

class MyModel extends \yii\base\Model {
    
    public $id;
    public $name;
    public $captcha;
    public function rules() {
        return [
            [['id', 'name'], 'required'],
            ['captcha', CaptchaValidator::className()]
        ];
    }
}
```

And add the widget to the ActiveField of the captcha attribute in your view.

```php
<?php
//.../views/mymodel/create.php
use jlorente\captcha\Captcha;

$form = ActiveForm::begin([
            'id' => 'my-form',
]);

echo $form->field($this->model, 'id');
echo $form->field($this->model, 'name');
echo $form->field($this->model, 'captcha', [
    'template' => "{input}\n{hint}\n{error}"
])->widget(Captcha::className());

// In this example the template attribute is provided to the ActiveField in order to hide the label of the captcha attribute.
```

Now the captcha will be shown only if there are many requests from the same IP for the current model.

##Further considerations

A request is counted when the CaptchaValidator is used, so if the validate method 
of the Model isn't called on form submit, the captcha will never be shown.

This module is an extension of the captcha functionality that comes with the Yii 2.0 
framework to provide additional functionality, so if you want to see more 
options and configurations of the widget, validator and action please refer to 
the [manual](http://www.yiiframework.com/doc-2.0/yii-captcha-captcha.html).

## License 
Copyright &copy; 2015 José Lorente Martín <jose.lorente.martin@gmail.com>.
Licensed under the MIT license. See LICENSE.txt for details.
