<?php
/* @var $this yii\web\View */
use navatech\language\Translate;

/* @var $model \navatech\language\models\Language */
$this->params['breadcrumbs'][] = [
	'label' => 'Languages',
	'url'   => ['list'],
];
$this->params['breadcrumbs'][] = Translate::create();
?>
<div class="page-header">
	<h1><?= Translate::language() ?>
		<small>
			<i class="ace-icon fa fa-angle-double-right"></i> <?= Translate::add_a_new() ?>
		</small>
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

