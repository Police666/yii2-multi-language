<?php
/* @var $this yii\web\View */
/* @var $model app\models\Employee */
$this->params['breadcrumbs'][] = [
	'label' => 'Languages',
	'url'   => ['list'],
];
$this->params['breadcrumbs'][] = "Create";
?>
<div class="page-header">
	<h1>Dashboard
		<small><i class="ace-icon fa fa-angle-double-right"></i> Add a new language</small>
	</h1>
</div>
<div class="row">
	<div class="col-xs-12">
		<div class="row">
			<div class="space-6"></div>
			<?= $this->render('_form', [
				'model' => $model,
			]) ?>
		</div>
	</div>
</div>

