<?php
/**
 * Created by Navatech.
 * @project Yii2 Multi Language
 * @author  Phuong
 * @email   phuong17889[at]gmail.com
 * @date    04/02/2016
 * @time    1:48 SA
 * @since   1.0.1
 */
namespace navatech\language\models;

use navatech\language\db\ActiveRecord;
use navatech\language\helpers\LanguageHelper;
use navatech\language\Translate;
use yii\base\InvalidParamException;
use yii\db\ActiveQuery;

/**
 * This is the model class for table "phrase_translate".
 * The followings are the available columns in table 'phrase_translate':
 * @property integer  $id
 * @property integer  $phrase_id
 * @property integer  $language_id
 * @property string   $value
 * @property Phrase   $phrase
 * @property Language $language
 */
class PhraseTranslate extends ActiveRecord {

	/**
	 * @return string the table name
	 */
	public static function tableName() {
		return '{{%phrase_translate}}';
	}

	/**
	 * @return array validation rules
	 * @see scenarios()
	 */
	public function rules() {
		return [
			[
				[
					'phrase_id',
					'language_id',
				],
				'required',
			],
			[
				[
					'phrase_id',
					'language_id',
				],
				'integer',
			],
			[
				[
					'id',
					'phrase_id',
					'language_id',
					'value',
				],
				'safe',
			],
		];
	}

	/**
	 * @return array attribute labels (name => label)
	 * @see generateAttributeLabel()
	 */
	public function attributeLabels() {
		return [
			'id'          => 'No.',
			'phrase_id'   => Translate::phrase(),
			'language_id' => Translate::language(),
			'value'       => Translate::translate(),
		];
	}

	/**
	 * @return ActiveQuery
	 * @since 1.0.0
	 */
	public function getLanguage() {
		return $this->hasOne(Language::className(), ['id' => 'language_id']);
	}

	/**
	 * @return ActiveQuery
	 * @since 1.0.0
	 */
	public function getPhrase() {
		return $this->hasOne(Phrase::className(), ['id' => 'phrase_id']);
	}

	/**
	 * {@inheritDoc}
	 */
	public function afterSave($insert, $changedAttributes) {
		parent::afterSave($insert, $changedAttributes);
		LanguageHelper::setData($this);
	}

	/**
	 * @return boolean whether the record should be deleted. Defaults to true.
	 * @throws InvalidParamException
	 */
	public function beforeDelete() {
		if (LanguageHelper::removeAllData($this)) {
			return parent::beforeDelete();
		}
		return false;
	}
}