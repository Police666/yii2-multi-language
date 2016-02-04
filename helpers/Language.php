<?php
/**
 * Created by Navatech.
 * @project Yii2 Multi Language
 * @author  Phuong
 * @email   phuong17889[at]gmail.com
 * @date    04/02/2016
 * @time    11:03 SA
 */
namespace navatech\language\helpers;

use kartik\editable\Editable;
use kartik\popover\PopoverX;
use navatech\language\models\Language as LanguageModel;
use navatech\language\models\Phrase;
use navatech\language\models\Phrase as PhraseModel;
use navatech\language\models\PhraseMeta as PhraseMetaModel;
use Yii;

class Language {

	/**
	 * @param null $code
	 *
	 * @return array|string
	 */
	public static function url($code = null) {
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

	/**
	 * This function will return string which translated follow base language
	 *
	 * @param $name          string
	 * @param $params        array
	 * @param $language_code string
	 *
	 * @return string return translated value
	 */
	public static function t($name, $params = array(), $language_code = null) {
		if($language_code == null) {
			$language_code = Yii::$app->language;
		}
		$language_id = LanguageModel::getIdByCode($language_code);
		$phrase_id   = PhraseModel::getIdByName($name);
		if($phrase_id != 0 && $language_id != 0) {
			/**@var $model PhraseMetaModel */
			$model = PhraseMetaModel::findOne([
				'phrase_id'   => $phrase_id,
				'language_id' => $language_id,
			]);
			if($model != null) {
				$model = PhraseMetaModel::findOne("phrase_id = " . $phrase_id . " AND value <> '' ORDER BY language_id ASC");
			}
			if($model) {
				$value = $model->value;
				if($params != null) {
					foreach($params as $key => $param) {
						$value = str_replace('{' . ($key + 1) . '}', $param, $value);
					}
				}
				return trim($value);
			} else {
				return 'error: phrase "' . $name . '" not found';
			}
		} else {
			$phrase       = new PhraseModel();
			$phrase->name = $name;
			if($phrase->save()) {
				$phraseMeta              = new PhraseMetaModel();
				$phraseMeta->phrase_id   = $phrase->getPrimaryKey();
				$phraseMeta->language_id = $language_id;
				$phraseMeta->value       = 'error: phrase [' . $name . '] not found';
				$phraseMeta->save();
			}
			return 'error: phrase [' . $name . '] not found';
		}
	}

	/**
	 * @param $phrase Phrase
	 *
	 * @return array|mixed
	 */
	public static function phraseColumns($phrase) {
		$columns    = [];
		$columns[]  = ['class' => 'kartik\grid\SerialColumn'];
		$columns[]  = ['attribute' => 'name'];
		$attributes = $phrase->attributeLabels();
		foreach($phrase->_dynamicField as $key => $value) {
			$columns[] = array(
				'attribute'       => $key,
				'header'          => '<a href="#">' . $attributes[$key] . '</a>',
				'value'           => function (Phrase $model) use ($key) {
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