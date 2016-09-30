<?php
use navatech\language\models\Language;
use navatech\language\Translate;
use yii\web\View;

/* @var $this View */
/* @var $model Language */
$this->params['breadcrumbs'][] = [
	'label' => Translate::language(),
	'url'   => ['list'],
];
$this->params['breadcrumbs'][] = Translate::update();
?>
<div class="navatech-language">
	<div class="col-sm-12">
		<div class="page-header">
			<h1><?= Translate::language() ?>
				<small>
					<i class="ace-icon fa fa-angle-double-right"></i> <?= Translate::update() ?>
				</small>
			</h1>
		</div>
		<div class="space-6"></div>
		<?= $this->render('_form', [
			'model' => $model,
		]) ?>
	</div>
</div>