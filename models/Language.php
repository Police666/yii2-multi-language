<?php
/**
 * Created by Navatech.
 * @project nic
 * @author  Phuong
 * @email   notteen[at]gmail.com
 * @date    04/02/2016
 * @time    1:49 SA
 */
namespace navatech\language\models;

use Yii;
use yii\db\ActiveRecord;

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
				'language_id, name, code, status, country',
				'safe',
				'on' => 'search',
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
			'language_id' => 'No.',
			'name'        => 'Name',
			'code'        => 'Code',
			'country'     => 'Country',
			'status'      => 'Status',
		);
	}

	public static function getAllLanguages($attributes = array()) {
		if($attributes == null) {
			$attributes['status'] = 1;
		}
		return self::findOne($attributes);
	}

	public static function getUrl($code = null) {
		if($code == null) {
			$code = Yii::$app->language;
		}
		$url = $_SERVER['REQUEST_URI'];
		if(is_int(strpos($url, 'language'))) {
			$url = explode("language", $url);
			$url = $url[0];
			$url .= 'language=' . $code;
		} else {
			if(is_int(strpos($url, '?'))) {
				$url .= '&language=' . $code;
			} else {
				$url .= '?language=' . $code;
			}
		}
		return $url;
	}
}