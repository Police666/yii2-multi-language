<?php
/**
 * Created by Navatech.
 * @project Yii2 Multi Language
 * @author  Phuong
 * @email   notteen[at]gmail.com
 * @date    04/02/2016
 * @time    1:48 SA
 */
namespace navatech\language\models;

use yii\db\ActiveRecord;

/**
 * This is the model class for table "phrase".
 *
 * @property integer    $id
 * @property string     $name
 * @property Language[] $languages
 */
class Phrase extends ActiveRecord {

	public $_dynamicData   = [];

	public $_dynamicFields = [];

	public $languages      = [];

	/**
	 * @return string name of table
	 */
	public static function tableName() {
		return '{{%phrase}}';
	}

	/**
	 * @param string $name
	 *
	 * @return mixed|null
	 */
	public function __get($name) {
		if(!empty($this->_dynamicFields[$name])) {
			if(!empty($this->_dynamicData[$name])) {
				return $this->_dynamicData[$name];
			} else {
				return null;
			}
		} else {
			return parent::__get($name);
		}
	}

	/**
	 * @param string $name
	 * @param mixed  $val
	 *
	 * @return mixed
	 */
	public function __set($name, $val) {
		if(!empty($this->_dynamicFields[$name])) {
			$this->_dynamicData[$name] = $val;
		} else {
			parent::__set($name, $val);
		}
	}

	/**
	 * void
	 */
	public function init() {
		$this->languages = Language::getAllLanguages();
		foreach($this->languages as $language) {
			$this->_dynamicFields[$language->code] = $language->name;
		}
	}

	/**
	 * This will set dynamic field
	 */
	public function setDynamicField() {
		$num = 0;
		foreach($this->languages as $language) {
			$set = false;
			foreach($this->getPhraseMeta() as $phrase_meta) {
				if($phrase_meta->language_id == $language->id) {
					$this->__set($language->code, $phrase_meta->value);
					$set = true;
					break;
				}
			}
			if(!$set) {
				$this->__set($language->code, '');
			}
			$num ++;
		}
	}

	/**
	 * @return array of rule
	 */
	public function rules() {
		$code = [];
		foreach($this->languages as $language) {
			$code[] = $language->code;
		}
		return array(
			array(
				'name',
				'required',
			),
			array(
				'id, name, ' . implode(', ', $code),
				'safe',
			),
		);
	}

	/**
	 * This will return all PhraseMeta relations of Phrase
	 * @return \yii\db\ActiveQuery|PhraseMeta[]
	 */
	public function getPhraseMeta() {
		return $this->hasMany(PhraseMeta::className(), ['phrase_id' => 'id']);
	}

	/**
	 * @return array of this attributes
	 */
	public function attributes() {
		$attributes = parent::attributes();
		return array_merge($attributes, array_keys($this->_dynamicFields));
	}

	/**
	 * @return array of this attribute labels
	 */
	public function attributeLabels() {
		$labels = parent::attributeLabels();
		foreach($this->languages as $language) {
			$labels[$language->code] = $language->name;
		}
		return $labels;
	}

	/**
	 * @param $name
	 *
	 * @return int
	 */
	public static function getIdByName($name) {
		$model = self::findOne(['name' => $name]);
		if($model) {
			return $model->id;
		} else {
			return 0;
		}
	}
}