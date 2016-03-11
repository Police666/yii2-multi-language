<?php
/**
 * Created by Navatech.
 * @project Yii2 Multi Language
 * @author  Phuong
 * @email   phuong17889[at]gmail.com
 * @date    04/02/2016
 * @time    1:46 SA
 * @since   1.0.2
 */
namespace navatech\language;

use navatech\language\helpers\MultiLanguageHelpers;

class Module extends \yii\base\Module {

	const VERSION = '2.0.0';

	/**
	 * @var string
	 * @since 2.0.0
	 */
	public $suffix = 'translate';

	/**
	 * {@inheritDoc}
	 */
	public function init() {
		parent::init();
		MultiLanguageHelpers::getData(\Yii::$app->language);
	}
}