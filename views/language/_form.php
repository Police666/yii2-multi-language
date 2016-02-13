<?php
use navatech\language\Translate;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model navatech\language\models\Language */
/* @var $form yii\widgets\ActiveForm */
?>
<?php $form = ActiveForm::begin([
	'options' => [
		'class' => 'language-form',
	],
]); ?>
<?php $form = ActiveForm::begin(); ?>
<div class="project-form">
	<div class="col-md-6 col-md-offset-3">

		<?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

		<?= $form->field($model, 'code')->textInput(['maxlength' => true]) ?>

		<?= $form->field($model, 'country')->textInput(['maxlength' => true]) ?>

		<?= $form->field($model, 'status')->dropDownList([
			0 => 'Not in use',
			1 => 'In use',
		], ['prompt' => 'Choose status ...']) ?>

	</div>
</div>
<div class="clearfix form-actions">
	<div class="col-md-offset-3 col-md-6">
		<button class="btn btn-info" type="submit">
			<i class="ace-icon fa fa-check bigger-110"></i>
			<?= Translate::save() ?>
		</button>

		&nbsp; &nbsp; &nbsp;
		<button class="btn btn-back" type="reset">
			<i class="ace-icon fa fa-arrow-left bigger-110"></i>
			<?= Translate::back() ?>
		</button>
	</div>
</div>
<?php ActiveForm::end(); ?>
