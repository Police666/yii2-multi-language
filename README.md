# yii2-multi-language
Config:
````
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