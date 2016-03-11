Examples
--------

Example #1: current language translations are inserted to the model as normal attributes by default.

```php
//Assuming current language is english

$model = Post::findOne(1);
echo $model->title; //echo "English title"

//Now let's imagine current language is french
$model = Post::findOne(1);
echo $model->title; //echo "Titre en Français"

$model = Post::find()->localized('en')->one();
echo $model->title; //echo "English title"

//Current language is still french here
```

Example #2: if you use `translate()` in a `find()` query, every model translation is loaded as virtual attributes (title_en, title_fr, title_de, ...).

```php
$model = Post::find()->translate()->one();
echo $model->title_en; //echo "English title"
echo $model->title_fr; //echo "Titre en Français"
```

Behavior attributes
------------
Attributes marked as bold are required

Attribute | Description
----------|------------
languageField | The name of the language field of the translation table. Default is 'language'
langClassName | The name of translation model class. Default value is model name + Translate
langForeignKey | Name of the foreign key field of the translation table related to base model table.
tableName | The name of the translation table
**attributes** | Multilanguage attributes. Required only
Usage
-----

Here an example of base 'post' table :

```sql
CREATE TABLE IF NOT EXISTS `post` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `created_at` datetime NOT NULL,
    `updated_at` datetime NOT NULL,
    `enabled` tinyint(1) NOT NULL DEFAULT '1',
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
```

And its associated translation table (configured as default), assuming translated fields are 'title' and 'content':

```sql
CREATE TABLE IF NOT EXISTS `post_translate` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `post_id` int(11) NOT NULL,
    `language` varchar(6) NOT NULL,
    `title` varchar(255) NOT NULL,
    `content` TEXT NOT NULL,
    PRIMARY KEY (`id`),
    KEY `post_id` (`post_id`),
    KEY `language` (`language`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

ALTER TABLE `postLang`
ADD CONSTRAINT `postlang_ibfk_1` FOREIGN KEY (`post_id`) REFERENCES `post` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
```

Attaching this behavior to the model (Post in the example). Commented fields have default values.

```php
public function behaviors() {
    $attributes   = [
        'title',
        'content',
    ];
    return [
        'ml' => [
            'class'      => MultiLanguageBehavior::className(),
            'attributes' => $attributes,
        ],
    ];
}

public static function find() {
    return new MultiLanguageQuery(get_called_class());
}

public function rules() {
    return [
        [
            [
                'title',
                'content',
            ],
            'safe',
        ],
    ];
}
```

Form example to get all translate attributes:
```php
	$form = ActiveForm::begin();
	foreach ($model->getTranslateAttributes() as $translateAttribute) {
		echo $form->field($model, $translateAttribute)->textInput();
	}
	echo $form->field($model, 'created_at')->textInput();
	ActiveForm::end();
```
or get by attribute;
```php
	$form = ActiveForm::begin();
	foreach ($model->getTranslateAttributes('title') as $translateAttribute) {
		echo $form->field($model, $translateAttribute)->textInput();
	}
	foreach ($model->getTranslateAttributes('content') as $translateAttribute) {
		echo $form->field($model, $translateAttribute)->textInput();
	}
	echo $form->field($model, 'created_at')->textInput();
	ActiveForm::end();
```
