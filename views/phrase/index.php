<?php
/**
 * Created by Navatech.
 * @project nic
 * @author  Phuong
 * @email   phuong17889[at]gmail.com
 * @date    04/02/2016
 * @time    2:35 CH
 */
use kartik\grid\GridView;
use navatech\language\helpers\Language;

/* @var $this yii\web\View */
/* @var $searchModel \navatech\language\models\PhraseSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
$this->title                   = 'List phrases';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="page-header">
	<h1>Home Page
		<small><i class="ace-icon fa fa-angle-double-right"></i> List phrases</small>
	</h1>
</div>
<div class="row">
	<div class="col-xs-12">
		<div class="row">
			<div class="space-6"></div>
			<?= GridView::widget([
				'id'               => 'phrase',
				'dataProvider'     => $dataProvider,
				'filterModel'      => $searchModel,
				'export'           => [
					'fontAwesome' => true,
				],
				'responsive'       => true,
				'hover'            => true,
				'headerRowOptions' => ['class' => 'kartik-sheet-style'],
				'filterRowOptions' => ['class' => 'kartik-sheet-style'],
				'pjax'             => true,
				'columns'          => Language::phraseColumns($searchModel),
			]); ?>
		</div>
	</div>
</div>
