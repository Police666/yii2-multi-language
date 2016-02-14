<?php
/**
 * Created by Navatech.
 * @project nic
 * @author  Phuong
 * @email   phuong17889[at]gmail.com
 * @date    04/02/2016
 * @time    2:34 CH
 * @version 1.0.1
 */
namespace navatech\language\controllers;

use navatech\language\models\Language;
use navatech\language\models\PhraseMeta;
use navatech\language\models\PhraseSearch;
use Yii;
use yii\helpers\Json;
use yii\web\Controller;

class PhraseController extends Controller {

	/**
	 * @return string
	 * @since 1.0.0
	 * @throws \yii\base\ExitException|\yii\base\InvalidParamException
	 */
	public function actionIndex() {
		$searchModel  = new PhraseSearch();
		$dataProvider = $searchModel->search(Yii::$app->request->queryParams);
		if (Yii::$app->request->post('hasEditable')) {
			$post        = Yii::$app->request->post();
			$out         = Json::encode([
				'output'  => '',
				'message' => '',
			]);
			$language_id = Language::getIdByCode($post['editableAttribute']);
			$phrase_id   = $post['editableKey'];
			if ($language_id !== 0 && $phrase_id !== 0) {
				$model = PhraseMeta::findOne([
					'phrase_id'   => $phrase_id,
					'language_id' => $language_id,
				]);
				if ($model === null) {
					$model              = new PhraseMeta();
					$model->language_id = $language_id;
					$model->phrase_id   = $phrase_id;
				}
				$model->value = $post['Phrase'][0][$post['editableAttribute']];
				$model->save();
				$out = Json::encode([
					'output'  => $model->value,
					'message' => '',
				]);
			}
			echo $out;
			Yii::$app->end();
		}
		return $this->render('index', [
			'searchModel'  => $searchModel,
			'dataProvider' => $dataProvider,
		]);
	}
}