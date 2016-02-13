<?php
/**
 * Created by Navatech.
 * @project yii-basic
 * @author  Phuong
 * @email   phuong17889[at]gmail.com
 * @date    13/02/2016
 * @time    4:49 CH
 * @version 1.0.2
 */
namespace navatech\language;

use yii\web\AssetBundle;

class LanguageAsset extends AssetBundle {

	public $sourcePath = '@vendor/navatech/yii2-multi-language/assets';

	public $css        = [
		'style.css',
		'phoca-flags.css',
	];

	public $depends    = [
		'yii\web\YiiAsset',
		'yii\bootstrap\BootstrapAsset',
		'yii\bootstrap\BootstrapPluginAsset',
	];
}