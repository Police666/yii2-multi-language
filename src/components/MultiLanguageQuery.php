<?php
/**
 * Created by Navatech.
 * @project yii2-multi-language
 * @author  Phuong
 * @email   phuong17889[at]gmail.com
 * @date    03/03/2016
 * @time    12:46 SA
 */
namespace navatech\language\components;

use yii\db\ActiveQuery;

class MultiLanguageQuery extends ActiveQuery {

	use MultiLanguageTrait;
}