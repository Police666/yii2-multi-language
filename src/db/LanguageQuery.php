<?php
/**
 * Created by Navatech.
 * @project yii2-multi-language
 * @author  Phuong
 * @email   phuong17889[at]gmail.com
 * @date    03/03/2016
 * @time    12:46 SA
 * @since 2.0.0
 */
namespace navatech\language\db;

use yii\db\ActiveQuery;

/**
 * ActiveQuery represents a DB query associated with an Active Record class.
 *
 * An ActiveQuery can be a normal query or be used in a relational context.
 *
 * ActiveQuery instances are usually created by [[ActiveRecord::find()]] and [[ActiveRecord::findBySql()]].
 * Relational queries are created by [[ActiveRecord::hasOne()]] and [[ActiveRecord::hasMany()]].
 *
 * @since  2.0
 */
class LanguageQuery extends ActiveQuery {

	use LanguageTrait;
}