<?php
/**
 * Created by Navatech.
 * @project nic
 * @author  Phuong
 * @email   phuong17889[at]gmail.com
 * @date    04/02/2016
 * @time    2:34 CH
 */
namespace navatech\language\controllers;

use navatech\language\models\Language;
use navatech\language\models\PhraseMeta;
use navatech\language\models\PhraseSearch;
use navatech\language\helpers\Language as LanguageHelper;
use Yii;
use yii\helpers\Json;
use yii\web\Controller;

class PhraseController extends Controller {

	public function actionIndex() {
		echo '<pre>';
		print_r((new LanguageHelper)->one);
		die;
		$searchModel  = new PhraseSearch();
		$dataProvider = $searchModel->search(Yii::$app->request->queryParams);
		if(Yii::$app->request->post('hasEditable')) {
			$post        = Yii::$app->request->post();
			$out         = Json::encode([
				'output'  => '',
				'message' => '',
			]);
			$language_id = Language::getIdByCode($post['editableAttribute']);
			$phrase_id   = $post['editableKey'];
			if($language_id != 0 && $phrase_id != 0) {
				$model = PhraseMeta::findOne([
					'phrase_id'   => $phrase_id,
					'language_id' => $language_id,
				]);
				if($model === null) {
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