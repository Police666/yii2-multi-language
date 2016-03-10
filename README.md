# yii2-multi-language

[![Packagist Version](https://img.shields.io/packagist/v/navatech/yii2-multi-language.svg?style=flat)](https://packagist.org/packages/navatech/yii2-multi-language)
[![Total Downloads](https://img.shields.io/packagist/dt/navatech/yii2-multi-language.svg?style=flat)](https://packagist.org/packages/navatech/yii2-multi-language)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/navatech/yii2-multi-language/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/navatech/yii2-multi-language/?branch=master)
[![Build Status](https://scrutinizer-ci.com/g/navatech/yii2-multi-language/badges/build.png?b=master)](https://scrutinizer-ci.com/g/navatech/yii2-multi-language/build-status/master)

This module allow you to create multi language use database.
By default, Yii use Yii::t() for multi language.
But you must stored the sentences on file, and it never suggested keywords for you.
Now you can store it on mySql.

## Requirements
* [Yii 2](https://packagist.org/packages/yiisoft/yii2)
* [Yii 2 Bootstrap](https://packagist.org/packages/yiisoft/yii2-bootstrap)
* [Kartik-v Yii 2 Grid](https://packagist.org/packages/kartik-v/yii2-grid)
* [Kartik-v Yii 2 editable](https://packagist.org/packages/kartik-v/yii2-editable)
* [Navatech Yii 2 Locale Urls](https://packagist.org/packages/navatech/yii2-localeurls)

## Install & config:
Install with composer:
````
composer require navatech/yii2-multi-language "@dev"
````

Config:
~~~
[php]
    'language'   => 'en', //TODO Change this to 2 characters
    .....................
    'components' => [
        'urlManager' => [
            'class'               => 'navatech\localeurls\UrlManager',
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
        /*TODO uncommented if you want to custom view*/
        //'viewPath' => '@app/vendor/navatech/yii2-multi-language/src/views',
        /*TODO uncommented if you want to change suffix of translated table / model.
        should be one word, lowercase only.*/
        //'suffix' => 'translate',
        ],
    ],
~~~
Run Yii Migration, you will have two default language (English & Vietnamese):
```
php yii migrate/up --migrationPath=@vendor/navatech/yii2-multi-language/src/migrations
```

## Usage
[### LanguageWidget](https://github.com/navatech/yii2-multi-language/blob/2.0.dev/docs/widget.md)
[### Multi language on model](https://github.com/navatech/yii2-multi-language/blob/2.0.dev/docs/multi.md)
[### Phrase](http://google.com)
