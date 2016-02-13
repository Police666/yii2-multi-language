# yii2-multi-language

[![Packagist Version](https://img.shields.io/packagist/v/navatech/yii2-multi-language.svg?style=flat)](https://packagist.org/packages/navatech/yii2-multi-language)
[![Total Downloads](https://img.shields.io/packagist/dt/navatech/yii2-multi-language.svg?style=flat)](https://packagist.org/packages/navatech/yii2-multi-language)
[![Build Status](https://travis-ci.org/navatech/yii2-multi-language.svg?branch=master)](https://travis-ci.org/navatech/yii2-multi-language)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/navatech/yii2-multi-language/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/navatech/yii2-multi-language/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/navatech/yii2-multi-language/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/navatech/yii2-multi-language/?branch=master)


Install:
````
composer require navatech/yii2-multi-language "@dev"
````
Config:
````
    'language'   => 'en', //TODO Change this to 2 words
    .....................
    'components' => [
        'urlManager' => [
            'enablePrettyUrl'     => true,
            'showScriptName'      => false,
            'enableStrictParsing' => false,
        ],
    ],
    'modules'    => [
        'gridview' => [
            'class' => '\kartik\grid\Module',
        ],
        'language' => [
        'class'    => '\navatech\language\Module',
        //REQUIRED if you want to custom view
        'viewPath' => '@app/vendor/navatech/yii2-multi-language/src/views',
        ],
    ],
````
Migration:
```
php yii migrate/up --migrationPath=@vendor/navatech/yii2-multi-language/migrations
```
Usage Way 1:
````
$translate = new \navatech\language\Translate();
print_r($translate->about);
````
Usage Way 2:
````
use \navatech\language\Translate as Trans;
..........................
$translated = Trans::about($parameters = [], $language_code = 'en');
print_r($translated);
````
Management:
````
http://yii2.demo/web/language/index/list
http://yii2.demo/web/language/index/create
http://yii2.demo/web/language/index/update
http://yii2.demo/web/language/phrase/index
````