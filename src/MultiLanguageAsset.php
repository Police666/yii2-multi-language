<?php
/**
 * Created by Navatech.
 * @project Yii2 Multi Language
 * @author  Phuong
 * @email   phuong17889[at]gmail.com
 * @date    13/02/2016
 * @time    4:49 CH
 * @version 1.0.2
 */
namespace navatech\language;

use yii\web\AssetBundle;

class MultiLanguageAsset extends AssetBundle {

	public $sourcePath = '@vendor/navatech/yii2-multi-language/src/assets';

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