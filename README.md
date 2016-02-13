# yii2-multi-language

[![Packagist Version](https://img.shields.io/packagist/v/navatech/yii2-multi-language.svg?style=flat-square)](https://packagist.org/packages/omgdef/yii2-multilingual-behavior)
[![Total Downloads](https://img.shields.io/packagist/dt/omgdef/yii2-multilingual-behavior.svg?style=flat-square)](https://packagist.org/packages/omgdef/yii2-multilingual-behavior)
[![Build Status](https://img.shields.io/travis/OmgDef/yii2-multilingual-behavior/master.svg?style=flat-square)](https://travis-ci.org/OmgDef/yii2-multilingual-behavior)
[![Code Quality](https://img.shields.io/scrutinizer/g/omgdef/yii2-multilingual-behavior/master.svg?style=flat-square)](https://scrutinizer-ci.com/g/OmgDef/yii2-multilingual-behavior)
[![Code Coverage](https://img.shields.io/scrutinizer/coverage/g/omgdef/yii2-multilingual-behavior/master.svg?style=flat-square)](https://scrutinizer-ci.com/g/OmgDef/yii2-multilingual-behavior)


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
        'viewPath' => '@app/vendor/navatech/yii2-multi-language/views',
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