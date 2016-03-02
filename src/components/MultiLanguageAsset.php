<?php
/**
 * Created by Navatech.
 * @project Yii2 Multi Language
 * @author  Phuong
 * @email   phuong17889[at]gmail.com
 * @date    13/02/2016
 * @time    4:49 CH
 * @since   1.0.2
 */
namespace navatech\language\components;

use yii\web\AssetBundle;

class MultiLanguageAsset extends AssetBundle {

	/**
	 * Initializes the bundle.
	 * If you override this method, make sure you call the parent implementation in the last.
	 */
	public function init() {
		parent::init();
		$this->depends    = [
			'yii\web\YiiAsset',
			'yii\bootstrap\BootstrapAsset',
			'yii\bootstrap\BootstrapPluginAsset',
		];
		$this->css        = [
			'style.css',
			'phoca-flags.css',
		];
		$this->sourcePath = '@vendor/navatech/yii2-multi-language/src/assets';
	}
}