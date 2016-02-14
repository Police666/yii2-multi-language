<?php
/**
 * Created by Navatech.
 * @project Yii2 Multi Language
 * @author  Phuong
 * @email   phuong17889[at]gmail.com
 * @date    04/02/2016
 * @time    1:48 SA
 * @version 1.0.1
 */
namespace navatech\language\models;

use kartik\editable\Editable;
use kartik\popover\PopoverX;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "phrase".
 *
 * @property integer    $id
 * @property string     $name
 * @property Language[] $languages
 */
class Phrase extends ActiveRecord {

	public $_dynamicData  = [];

	public $_dynamicField = [];

	public $languages     = [];

	/**
	 * @inheritdoc
	 */
	public static function tableName() {
		return '{{%phrase}}';
	}

	public static function __getStatic() {
	}

	/**
	 * @inheritdoc
	 */
	public function __get($name) {
		if (!empty($this->_dynamicField[$name])) {
			if (!empty($this->_dynamicData[$name])) {
				return $this->_dynamicData[$name];
			} else {
				return null;
			}
		} else {
			return parent::__get($name);
		}
	}

	/**
	 * @inheritdoc
	 */
	public function __set($name, $val) {
		if (!empty($this->_dynamicField[$name])) {
			$this->_dynamicData[$name] = $val;
		} else {
			parent::__set($name, $val);
		}
	}

	/**
	 * @inheritdoc
	 */
	public function init() {
		$this->languages = Language::getAllLanguages();
		foreach ($this->languages as $language) {
			$this->_dynamicField[$language->code] = $language->name;
		}
	}

	/**
	 * This will set dynamic field
	 * @since 1.0.0
	 */
	public function setDynamicField() {
		foreach ($this->languages as $language) {
			$set = false;
			foreach ($this->getPhraseMeta() as $phrase_meta) {
				if ($phrase_meta->language_id == $language->id) {
					$this->__set($language->code, $phrase_meta->value);
					$set = true;
					break;
				}
			}
			if (!$set) {
				$this->__set($language->code, '');
			}
		}
	}

	/**
	 * @inheritdoc
	 */
	public function rules() {
		$code = [];
		foreach ($this->languages as $language) {
			$code[] = $language->code;
		}
		return [
			[
				['name'],
				'required',
			],
			[
				array_merge([
					'id',
					'name',
				], $code),
				'safe',
			],
		];
	}

	/**
	 * This will return all PhraseMeta relations of Phrase
	 * @return PhraseMeta[]
	 * @since 1.0.2
	 */
	public function getPhraseMeta() {
		return $this->hasMany(PhraseMeta::className(), ['phrase_id' => 'id'])->all();
	}

	/**
	 * @inheritdoc
	 */
	public function attributes() {
		$attributes = parent::attributes();
		return array_merge($attributes, array_keys($this->_dynamicField));
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels() {
		$labels = parent::attributeLabels();
		foreach ($this->languages as $language) {
			$labels[$language->code] = $language->name;
		}
		return $labels;
	}

	/**
	 *
	 * @param $name
	 *
	 * @return int
	 * @since 1.0.0
	 */
	public static function getIdByName($name) {
		$model = self::findOne(['name' => $name]);
		if ($model) {
			return $model->id;
		} else {
			return 0;
		}
	}

	/**
	 * @return array|mixed
	 * @since 1.0.0
	 */
	public function phraseColumns() {
		$columns    = [];
		$columns[]  = ['class' => 'kartik\grid\SerialColumn'];
		$columns[]  = ['attribute' => 'name'];
		$attributes = $this->attributeLabels();
		foreach ($this->_dynamicField as $key => $value) {
			$columns[] = array(
				'attribute'       => $key,
				'header'          => '<a href="#">' . $attributes[$key] . '</a>',
				'value'           => function(Phrase $model) use ($key) {
					$model->setDynamicField();
					return $model->$key;
				},
				'class'           => 'kartik\grid\EditableColumn',
				'refreshGrid'     => true,
				'editableOptions' => [
					'inputType' => Editable::INPUT_TEXTAREA,
					'placement' => PopoverX::ALIGN_TOP_LEFT,
					'options'   => [
						'cols' => '200',
					],
				],
			);
		}
		$columns[] = [
			'class'    => 'kartik\grid\ActionColumn',
			'template' => '{delete}',
		];
		return $columns;
	}
}