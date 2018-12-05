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
namespace navatech\language\assets;

use yii\web\AssetBundle;

/**
 * AssetBundle represents a collection of asset files, such as CSS, JS, images.
 *
 * Each asset bundle has a unique name that globally identifies it among all asset bundles used in an application.
 * The name is the [fully qualified class name](http://php.net/manual/en/language.namespaces.rules.php)
 * of the class representing it.
 *
 * An asset bundle can depend on other asset bundles. When registering an asset bundle
 * with a view, all its dependent asset bundles will be automatically registered.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since  2.0
 */
class LanguageAsset extends AssetBundle {

	/**
	 * Initializes the bundle.
	 * If you override this method, make sure you call the parent implementation in the last.
	 */
	public function init() {
		parent::init();
		$this->depends    = [
			'yii\web\YiiAsset',
			'yii\bootstrap4\BootstrapAsset',
			'yii\bootstrap4\BootstrapPluginAsset',
		];
		$this->css        = [
			'css/style.css',
			'css/phoca-flags.css',
		];
		$this->sourcePath = '@vendor/police666/yii2-multi-language/src/web';
	}
}