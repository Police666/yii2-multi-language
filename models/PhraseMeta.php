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
 * This is the model class for table "phrase_meta".
 * The followings are the available columns in table 'phrase_meta':
 * @property integer $id
 * @property integer $phrase_id
 * @property integer $language_id
 * @property string  $value
 */
class PhraseMeta extends ActiveRecord {

	/**
	 * @return string name of table
	 */
	public static function tableName() {
		return '{{%phrase_meta}}';
	}

	/**
	 * @return array
	 */
	public function rules() {
		return array(
			array(
				'phrase_id, language_id',
				'required',
			),
			array(
				'phrase_id, language_id',
				'numerical',
				'integerOnly' => true,
			),
			array(
				'id, phrase_id, language_id, value',
				'safe',
			),
		);
	}

	/**
	 * @return array of attribute labels
	 */
	public function attributeLabels() {
		return array(
			'id'          => 'No.',
			'phrase_id'   => 'Phrase',
			'language_id' => 'Language',
			'value'       => 'Translate',
		);
	}

	public function getLanguage() {
		return $this->hasOne(Language::className(), ['id' => 'language_id']);
	}

	public function getPhrase() {
		return $this->hasOne(Phrase::className(), ['id' => 'phrase_id']);
	}
}