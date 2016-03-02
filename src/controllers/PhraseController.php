<?php
/**
 * Created by Navatech.
 * @project    nic
 * @author     Phuong
 * @email      phuong17889[at]gmail.com
 * @created    04/02/2016 2:34 CH
 * @updated    03/03/2016 00:38 SA
 * @since      2.0.0
 */
namespace navatech\language\controllers;

use navatech\language\models\Language;
use navatech\language\models\Phrase;
use navatech\language\models\PhraseMeta;
use navatech\language\models\PhraseSearch;
use navatech\language\Translate;
use Yii;
use yii\filters\VerbFilter;
use yii\helpers\Json;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

class PhraseController extends Controller {

	/**
	 * {@inheritDoc}
	 */
	public function behaviors() {
		$behaviors = [
			'verbs' => [
				'class'   => VerbFilter::className(),
				'actions' => [
					'delete' => ['post'],
				],
			],
		];
		if (class_exists('navatech\\role\\Module')) {
			$behaviors['role'] = [
				'class'   => \navatech\role\filters\RoleFilter::className(),
				'name'    => Translate::x_management([Translate::language()]),
				'actions' => [
					'index'  => Translate::lists(),
					'delete' => Translate::delete(),
				],
			];
		}
		return $behaviors;
	}

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

	/**
	 * Deletes an existing Financial model.
	 * If deletion is successful, the browser will be redirected to the 'index' page.
	 *
	 * @param integer $id
	 *
	 * @return mixed
	 */
	public function actionDelete($id) {
		$this->findModel($id)->delete();
		if (!Yii::$app->request->get('project') && !Yii::$app->request->isAjax) {
			return $this->redirect(Yii::$app->request->referrer);
		}
		return false;
	}

	/**
	 * Finds the Language model based on its primary key value.
	 * If the model is not found, a 404 HTTP exception will be thrown.
	 *
	 * @param integer $id
	 *
	 * @return Phrase the loaded model
	 * @throws NotFoundHttpException if the model cannot be found
	 * @since 1.0.0
	 */
	protected function findModel($id) {
		if (($model = Phrase::findOne($id)) !== null) {
			return $model;
		} else {
			throw new NotFoundHttpException('The requested page does not exist.');
		}
	}
}