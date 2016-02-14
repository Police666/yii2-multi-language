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

use navatech\language\MultiLanguage;
use navatech\language\Translate;
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
	 * @inheritdoc
	 */
	public static function tableName() {
		return '{{%phrase_meta}}';
	}

	/**
	 * @inheritdoc
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
	 * @inheritdoc
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
	 * @return Language
	 * @since 1.0.0
	 */
	public function getLanguage() {
		return $this->hasOne(Language::className(), ['id' => 'language_id'])->one();
	}

	/**
	 * @return Phrase
	 * @since 1.0.0
	 */
	public function getPhrase() {
		return $this->hasOne(Phrase::className(), ['id' => 'phrase_id'])->one();
	}

	/**
	 * @inheritdoc
	 */
	public function beforeSave($insert) {
		MultiLanguage::setData($this);
		return parent::beforeSave($insert);
	}

	/**
	 * @inheritdoc
	 */
	public function beforeDelete() {
		if (MultiLanguage::removeAllData($this)) {
			return parent::beforeDelete();
		}
		return false;
	}
}