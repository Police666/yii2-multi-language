<?php
/**
 * Created by Navatech.
 * @project    Yii2 Multi Language
 * @author     Phuong
 * @email      phuong17889[at]gmail.com
 * @created    04/02/2016 1:49 SA
 * @updated    03/03/2016 00:40 SA
 * @since      2.0.0
 */
namespace navatech\language\models;

use navatech\language\helpers\MultiLanguageHelpers;
use navatech\language\Translate;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "language".
 *
 * @property integer $id
 * @property string  $name
 * @property string  $code
 * @property string  $country
 * @property int     $status
 */
class Language extends ActiveRecord {

	/**
	 * @return string the table name
	 */
	public static function tableName() {
		return '{{%language}}';
	}

	/**
	 * @return array validation rules
	 * @see scenarios()
	 */
	public function rules() {
		return [
			[
				[
					'name',
					'code',
					'country',
				],
				'required',
			],
			[
				['status'],
				'integer',
			],
			[
				[
					'name',
					'code',
					'country',
				],
				'string',
				'max' => 255,
			],
			[
				[
					'id',
					'name',
					'code',
					'status',
					'country',
				],
				'safe',
			],
		];
	}

	/**
	 * @return PhraseTranslate[]
	 * @since 1.0.2
	 */
	public function getPhraseTranslate() {
		return $this->hasMany(PhraseTranslate::className(), ['language_id' => 'id'])->all();
	}

	/**
	 * @return array attribute labels (name => label)
	 * @see generateAttributeLabel()
	 */
	public function attributeLabels() {
		return [
			'id'      => 'No.',
			'name'    => Translate::name(),
			'code'    => Translate::code(),
			'country' => Translate::country(),
			'status'  => Translate::status(),
		];
	}

	/**
	 *
	 * @param array|null $attributes
	 *
	 * @return array|\yii\db\ActiveRecord[]|Language[]
	 * @since 1.0.0
	 */
	public static function getAllLanguages(array $attributes = []) {
		if ($attributes === null) {
			$attributes = ['status' => 1];
		}
		return self::find()->where($attributes)->all();
	}

	/**
	 *
	 * @param $code
	 *
	 * @return int
	 * @since 1.0.0
	 */
	public static function getIdByCode($code) {
		$model = self::findOne(['code' => $code]);
		if ($model) {
			return $model->id;
		} else {
			return 0;
		}
	}

	/**
	 * @param boolean $insert            whether this method called while inserting a record.
	 *                                   If false, it means the method is called while updating a record.
	 * @param array   $changedAttributes The old values of attributes that had changed and were saved.
	 *                                   You can use this parameter to take action based on the changes made for
	 *                                   example send an email when the password had changed or implement audit trail
	 *                                   that tracks all the changes.
	 *                                   `$changedAttributes` gives you the old attribute values while the active
	 *                                   record (`$this`) has already the new, updated values.
	 *
	 * @throws \yii\base\Exception|\yii\base\InvalidParamException
	 */
	public function afterSave($insert, $changedAttributes) {
		parent::afterSave($insert, $changedAttributes);
		MultiLanguageHelpers::setLanguages();
	}

	/**
	 * @return boolean whether the record should be deleted. Defaults to true.
	 * @throws \yii\base\Exception|\yii\base\InvalidParamException
	 */
	public function beforeDelete() {
		if (MultiLanguageHelpers::removeAllData($this->code)) {
			return parent::beforeDelete();
		}
		return false;
	}
}