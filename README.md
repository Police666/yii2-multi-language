# yii2-multi-language
Install:
````
composer require navatech/yii2-multi-language "@dev"
````
Config:
````
    'language'   => 'en',
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
            'class' => '\navatech\language\Module',
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
$translated = \navatech\language\Translate::about($parameters = [], $language_code = 'en');
print_r($translated);
````
Management:
````
http://yii2.demo/web/language/index/list
http://yii2.demo/web/language/index/create
http://yii2.demo/web/language/index/update
http://yii2.demo/web/language/phrase/index
````