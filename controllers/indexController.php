<?php
/**
 * Created by Navatech.
 * @project Yii2 Multi Language
 * @author  Phuong
 * @email   phuong17889[at]gmail.com
 * @date    04/02/2016
 * @time    1:29 CH
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

	public function actionList() {
		$searchModel  = new LanguageSearch();
		$dataProvider = $searchModel->search(Yii::$app->request->queryParams);
		return $this->render('list', [
			'searchModel'  => $searchModel,
			'dataProvider' => $dataProvider,
		]);
	}

	/**
	 * Creates a new Employee model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 * @return mixed
	 */
	public function actionCreate() {
		$model = new Language();
		if($model->load(Yii::$app->request->post())) {
			if($model->save()) {
				Yii::$app->getSession()->setFlash('message', 'Created');
				return $this->redirect(['list']);
			} else {
				Yii::$app->getSession()->setFlash('message', 'Something went wrong!');
			}
		}
		return $this->render('create', [
			'model' => $model,
		]);
	}

	/**
	 * Updates an existing Employee model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 *
	 * @param integer $id
	 *
	 * @return mixed
	 */
	public function actionUpdate($id) {
		$model = $this->findModel($id);
		if($model->load(Yii::$app->request->post())) {
			if($model->save()) {
				Yii::$app->getSession()->setFlash('message', 'Updated!');
				return $this->redirect(['list']);
			} else {
				Yii::$app->getSession()->setFlash('message', 'Something went wrong!');
			}
		}
		return $this->render('update', [
			'model' => $model,
		]);
	}

	/**
	 * Finds the Employee model based on its primary key value.
	 * If the model is not found, a 404 HTTP exception will be thrown.
	 *
	 * @param integer $id
	 *
	 * @return Language the loaded model
	 * @throws NotFoundHttpException if the model cannot be found
	 */
	protected function findModel($id) {
		if(($model = Language::findOne($id)) !== null) {
			return $model;
		} else {
			throw new NotFoundHttpException('The requested page does not exist.');
		}
	}
}