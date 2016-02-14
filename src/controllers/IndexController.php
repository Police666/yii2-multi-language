<?php
/**
 * Created by Navatech.
 * @project Yii2 Multi Language
 * @author  Phuong
 * @email   phuong17889[at]gmail.com
 * @date    04/02/2016
 * @time    1:29 CH
 * @version 1.0.1
 */
namespace navatech\language\controllers;

use navatech\language\models\Language;
use navatech\language\models\LanguageSearch;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

class IndexController extends Controller {

	public function actionIndex() {
		return $this->actionList();
	}

	/**
	 * @return string
	 * @since 1.0.0
	 */
	public function actionList() {
		$searchModel  = new LanguageSearch();
		$dataProvider = $searchModel->search(Yii::$app->request->queryParams);
		return $this->render('/language/list', [
			'searchModel'  => $searchModel,
			'dataProvider' => $dataProvider,
		]);
	}

	/**
	 * Creates a new Language model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 * @return mixed
	 * @since 1.0.0
	 */
	public function actionCreate() {
		$model = new Language();
		if ($model->load(Yii::$app->request->post())) {
			if ($model->save()) {
				Yii::$app->getSession()->setFlash('message', 'Created');
				return $this->redirect(['list']);
			} else {
				Yii::$app->getSession()->setFlash('message', 'Something went wrong!');
			}
		}
		return $this->render('/language/create', [
			'model' => $model,
		]);
	}

	/**
	 * Updates an existing Language model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 *
	 * @param integer $id
	 *
	 * @return mixed
	 * @since 1.0.0
	 */
	public function actionUpdate($id) {
		$model = $this->findModel($id);
		if ($model->load(Yii::$app->request->post())) {
			if ($model->save()) {
				Yii::$app->getSession()->setFlash('message', 'Updated!');
				return $this->redirect(['list']);
			} else {
				Yii::$app->getSession()->setFlash('message', 'Something went wrong!');
			}
		}
		return $this->render('/language/update', [
			'model' => $model,
		]);
	}

	/**
	 * Finds the Language model based on its primary key value.
	 * If the model is not found, a 404 HTTP exception will be thrown.
	 *
	 * @param integer $id
	 *
	 * @return Language the loaded model
	 * @throws NotFoundHttpException if the model cannot be found
	 * @since 1.0.0
	 */
	protected function findModel($id) {
		if (($model = Language::findOne($id)) !== null) {
			return $model;
		} else {
			throw new NotFoundHttpException('The requested page does not exist.');
		}
	}
}