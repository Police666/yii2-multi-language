<?php
/**
 * Created by Navatech.
 * @project    yii-basic
 * @author     Phuong
 * @email      phuong17889[at]gmail.com
 * @date       10/03/2016
 * @time       5:39 CH
 * @since      2.0.0
 */
namespace navatech\language\components;

use navatech\language\helpers\MultiLanguageHelpers;
use navatech\language\Module;
use Yii;
use yii\base\Behavior;
use yii\base\InvalidConfigException;
use yii\base\UnknownPropertyException;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\helpers\Url;
use yii\validators\Validator;

class MultiLanguageBehavior extends Behavior {

	/**
	 * @var ActiveRecord|$this
	 */
	public $owner;

	/**
	 * Multilanguage attributes
	 * @var array
	 */
	public $attributes;

	/**
	 * @var string the name of the translation table
	 */
	public $tableName;

	/**
	 * @var string the name of translation model class.
	 */
	public $langClassName;

	/**
	 * @var string the name of the foreign key field of the translation table related to base model table.
	 */
	public $langForeignKey;

	/**
	 * @var string the name of the lang field of the translation table. Default to 'language'.
	 */
	public $languageField = 'language';

	/**
	 * @var boolean whether to force deletion of the associated translations when a base model is deleted.
	 * Not needed if using foreign key with 'on delete cascade'.
	 * Default to true.
	 */
	public $forceDelete = true;

	/**
	 * @var array list available languages
	 */
	private $languages = [];

	/**
	 * @var string current default language
	 */
	private $language;

	/**
	 * @var int current primary key
	 */
	private $ownerPrimaryKey;

	/**
	 * @var array temp of values
	 */
	private $translateAttributes = [];

	/**
	 * @var Module
	 */
	private $module;

	/**
	 * {@inheritDoc}
	 */
	public function init() {
		parent::init();
		$this->module   = Yii::$app->getModule('language');
		$this->language = Yii::$app->language;
		foreach (MultiLanguageHelpers::getLanguages() as $language) {
			$this->languages[$language['code']] = $language['name'];
		}
	}

	/**
	 * @inheritdoc
	 */
	public function events() {
		return [
			ActiveRecord::EVENT_AFTER_FIND      => 'afterFind',
			ActiveRecord::EVENT_AFTER_UPDATE    => 'afterUpdate',
			ActiveRecord::EVENT_AFTER_INSERT    => 'afterInsert',
			ActiveRecord::EVENT_AFTER_DELETE    => 'afterDelete',
			ActiveRecord::EVENT_BEFORE_VALIDATE => 'beforeValidate',
		];
	}

	/**
	 * {@inheritDoc}
	 */
	public function attach($owner) {
		/** @var ActiveRecord $owner */
		parent::attach($owner);
		$this->module   = Yii::$app->getModule('language');
		$this->language = Yii::$app->language;
		foreach (MultiLanguageHelpers::getLanguages() as $language) {
			$this->languages[$language['code']] = $language['name'];
		}
		if (empty($this->languages) || !is_array($this->languages)) {
			throw new InvalidConfigException('Please specify at least one of available languages on ' . Url::to(['language/index']), 101);
		}
		if (array_values($this->languages) !== $this->languages) {
			$this->languages = array_keys($this->languages);
		}
		if (empty($this->attributes) || !is_array($this->attributes)) {
			throw new InvalidConfigException('Please specify multilingual attributes for the ' . get_class($this) . ' in the ' . get_class($this->owner), 103);
		}
		if (!$this->langClassName) {
			$this->langClassName = get_class($this->owner) . ucfirst($this->module->suffix);
		}
		/** @var ActiveRecord $className */
		$className             = get_class($this->owner);
		$this->ownerPrimaryKey = $className::primaryKey()[0];
		if (!$this->langForeignKey) {
			$this->langForeignKey = $this->owner->tableName() . '_id';
		}
		if (!$this->tableName) {
			$this->tableName = $this->owner->tableName() . '_' . $this->module->suffix;
		}
		$rules      = $owner->rules();
		$validators = $owner->getValidators();
		foreach ($rules as $rule) {
			if ($rule[1] == 'unique') {
				continue;
			}
			$rule_attributes = is_array($rule[0]) ? $rule[0] : [$rule[0]];
			$attributes      = array_intersect($this->attributes, $rule_attributes);
			if (empty($attributes)) {
				continue;
			}
			$rule_attributes = [];
			foreach ($attributes as $key => $attribute) {
				foreach ($this->languages as $language) {
					$rule_attributes[] = $attribute . "_" . $language;
				}
			}
			$params = array_slice($rule, 2);
			if ($rule[1] !== 'required') {
				$validators[] = Validator::createValidator($rule[1], $owner, $rule_attributes, $params);
			} else {
				$validators[] = Validator::createValidator('safe', $owner, $rule_attributes, $params);
			}
		}
		$translation = new $this->langClassName;
		foreach ($this->languages as $lang) {
			foreach ($this->attributes as $attribute) {
				$this->setTranslateAttribute($attribute . "_" . $lang, $translation->$attribute);
				if ($lang == Yii::$app->language) {
					$this->setTranslateAttribute($attribute, $translation->$attribute);
				}
			}
		}
	}

	/**
	 * Handle 'beforeValidate' event of the owner.
	 */
	public function beforeValidate() {
		foreach ($this->attributes as $attribute) {
			$this->setTranslateAttribute($attribute, $this->getTranslateAttribute($attribute . '_' . $this->language));
		}
	}

	/**
	 * Handle 'afterFind' event of the owner.
	 */
	public function afterFind() {
		$owner = $this->owner;
		if ($owner->isRelationPopulated('translations') && $related = $owner->getRelatedRecords()['translations']) {
			$translations = $this->indexByLanguage($related);
			foreach ($this->languages as $lang) {
				foreach ($this->attributes as $attribute) {
					foreach ($translations as $translation) {
						if ($translation->{$this->languageField} == $lang) {
							$this->setTranslateAttribute($attribute . '_' . $lang, $translation->$attribute);
							if ($lang == Yii::$app->language) {
								$this->setTranslateAttribute($attribute, $translation->$attribute);
							}
						}
					}
				}
			}
		} else {
			if (!$owner->isRelationPopulated('translation')) {
				$owner->translation;
			}
			$translation = $owner->getRelatedRecords()['translation'];
			if ($translation) {
				foreach ($this->attributes as $attribute) {
					$owner->setTranslateAttribute($attribute, $translation->$attribute);
				}
			}
		}
		foreach ($this->attributes as $attribute) {
			if ($owner->hasAttribute($attribute) && $this->getTranslateAttribute($attribute)) {
				$owner->setAttribute($attribute, $this->getTranslateAttribute($attribute));
			}
		}
	}

	/**
	 * Handle 'afterInsert' event of the owner.
	 */
	public function afterInsert() {
		$this->saveTranslations();
	}

	/**
	 * Handle 'afterUpdate' event of the owner.
	 */
	public function afterUpdate() {
		/** @var ActiveRecord $owner */
		$owner = $this->owner;
		if ($owner->isRelationPopulated('translations')) {
			$translations = $this->indexByLanguage($owner->getRelatedRecords()['translations']);
			$this->saveTranslations($translations);
		}
	}

	/**
	 * Handle 'afterDelete' event of the owner.
	 */
	public function afterDelete() {
		if ($this->forceDelete) {
			/** @var ActiveRecord $owner */
			$owner = $this->owner;
			$owner->unlinkAll('translations', true);
		}
	}

	/**
	 * @inheritdoc
	 */
	public function canGetProperty($name, $checkVars = true) {
		return method_exists($this, 'get' . $name) || $checkVars && property_exists($this, $name) || $this->hasTranslateAttribute($name);
	}

	/**
	 * @inheritdoc
	 */
	public function canSetProperty($name, $checkVars = true) {
		return $this->hasTranslateAttribute($name);
	}

	/**
	 * @inheritdoc
	 */
	public function __get($name) {
		try {
			return parent::__get($name);
		} catch (UnknownPropertyException $e) {
			if ($this->hasTranslateAttribute($name)) {
				return $this->getTranslateAttribute($name);
			} else {
				throw $e;
			}
		}
	}

	/**
	 * @inheritdoc
	 */
	public function __set($name, $value) {
		try {
			parent::__set($name, $value);
		} catch (UnknownPropertyException $e) {
			if ($this->hasTranslateAttribute($name)) {
				$this->setTranslateAttribute($name, $value);
			} else {
				throw $e;
			}
		}
	}

	/**
	 * @inheritdoc
	 */
	public function __isset($name) {
		if (!parent::__isset($name)) {
			return $this->hasTranslateAttribute($name);
		} else {
			return true;
		}
	}

	/**
	 * Save related model
	 *
	 * @param array $translations
	 *
	 * @since 2.0.0
	 */
	private function saveTranslations($translations = []) {
		/** @var ActiveRecord $owner */
		$owner = $this->owner;
		foreach ($this->languages as $lang) {
			if (!isset($translations[$lang])) {
				/** @var ActiveRecord $translation */
				$translation                          = new $this->langClassName;
				$translation->{$this->languageField}  = $lang;
				$translation->{$this->langForeignKey} = $owner->getPrimaryKey();
			} else {
				$translation = $translations[$lang];
			}
			foreach ($this->attributes as $attribute) {
				$value = $this->getTranslateAttribute($attribute . '_' . $lang);
				if ($value !== null) {
					$translation->$attribute = $value;
				}
			}
			if ($translation->isNewRecord && !$translation->save()) {
				print_r($translation->getErrors());
			}
		}
	}

	/**
	 * @param mixed|string $name special attribute name if you want get only multi language for this column
	 *
	 * @return array
	 * @since 2.0.0
	 */
	public function getTranslateAttributes($name = null) {
		$attributes = [];
		if ($name != null) {
			foreach ($this->languages as $language) {
				$attributes[] = $name . '_' . $language;
			}
		} else {
			foreach ($this->attributes as $attribute) {
				foreach ($this->languages as $language) {
					$attributes[] = $attribute . '_' . $language;
				}
			}
		}
		return $attributes;
	}

	/**
	 * @param $records
	 *
	 * @return array
	 * @since 2.0.0
	 */
	protected function indexByLanguage($records) {
		$sorted = array();
		foreach ($records as $record) {
			$sorted[$record->{$this->languageField}] = $record;
		}
		unset($records);
		return $sorted;
	}

	/**
	 * Relation to model translations
	 * @return ActiveQuery
	 * @since 2.0.0
	 */
	public function getTranslations() {
		return $this->owner->hasMany($this->langClassName, [$this->langForeignKey => $this->ownerPrimaryKey]);
	}

	/**
	 * Relation to model translation
	 *
	 * @param $language
	 *
	 * @return ActiveQuery
	 * @since 2.0.0
	 */
	public function getTranslation($language = null) {
		if ($language == null) {
			$language = $this->language;
		}
		return $this->owner->hasOne($this->langClassName, [$this->langForeignKey => $this->ownerPrimaryKey])->where([$this->languageField => $language]);
	}

	/**
	 * Whether an attribute exists
	 *
	 * @param string $name the name of the attribute
	 *
	 * @return boolean
	 * @since 2.0.0
	 */
	public function hasTranslateAttribute($name) {
		return array_key_exists($name, $this->translateAttributes);
	}

	/**
	 * @param string $name  the name of the attribute
	 * @param string $value the value of the attribute
	 *
	 * @since 2.0.0
	 */
	public function setTranslateAttribute($name, $value) {
		$this->translateAttributes[$name] = $value;
	}

	/**
	 * @param string $name the name of the attribute
	 *
	 * @param null   $language
	 *
	 * @return string the attribute value
	 * @since 2.0.0
	 */
	public function getTranslateAttribute($name, $language = null) {
		if ($language !== null) {
			$attribute = $name . '_' . $language;
		} else {
			$attribute = $name;
		}
		return $this->hasTranslateAttribute($attribute) ? $this->translateAttributes[$attribute] : null;
	}
}
