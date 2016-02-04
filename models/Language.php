<?php
/**
 * Created by Navatech.
 * @project Yii2 Multi Language
 * @author  Phuong
 * @email   notteen[at]gmail.com
 * @date    04/02/2016
 * @time    1:49 SA
 */
namespace navatech\language\models;

use Yii;
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
	 * @return string
	 */
	public static function tableName() {
		return '{{%language}}';
	}

	/**
	 * @return array
	 */
	public function rules() {
		return array(
			array(
				'name, code, country',
				'required',
			),
			array(
				'status',
				'numerical',
				'integerOnly' => true,
			),
			array(
				'name, code, country',
				'length',
				'max' => 255,
			),
			array(
				'id, name, code, status, country',
				'safe',
			),
		);
	}

	/**
	 * @return \yii\db\ActiveQuery|PhraseMeta[]
	 */
	public function getPhraseMeta() {
		return $this->hasMany(PhraseMeta::className(), ['language_id' => 'id']);
	}

	/**
	 * @return array
	 */
	public function attributeLabels() {
		return array(
			'id'      => 'No.',
			'name'    => 'Name',
			'code'    => 'Code',
			'country' => 'Country',
			'status'  => 'Status',
		);
	}

	/**
	 * @param array $attributes
	 *
	 * @return array|\yii\db\ActiveRecord[]|Language[]
	 */
	public static function getAllLanguages($attributes = array()) {
		if($attributes == null) {
			$attributes['status'] = 1;
		}
		return self::find()->where($attributes)->all();
	}

	/**
	 * @param $code
	 *
	 * @return int
	 */
	public static function getIdByCode($code) {
		$model = self::findOne(array('code' => $code));
		if($model) {
			return $model->id;
		} else {
			return 0;
		}
	}
}