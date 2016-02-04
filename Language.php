<?php
/**
 * Created by Navatech.
 * @project Yii2 Multi Language
 * @author  Phuong
 * @email   phuong17889[at]gmail.com
 * @date    04/02/2016
 * @time    11:03 SA
 * @version 1.0.0
 */
namespace navatech\language;

use navatech\language\models\Language as LanguageModel;
use navatech\language\models\Phrase;
use Yii;
use yii\helpers\Json;

class Language {

	private $values;

	public function __construct() {
		$this->values = $this->getData(Yii::$app->language);
	}

	public function __get($name) {
		return $this->values[$name];
	}

	/**
	 * @param null $code
	 *
	 * @return array|string
	 * @since 1.0.0
	 */
	public static function url($code = null) {
		if($code == null) {
			$code = Yii::$app->language;
		}
		$url = $_SERVER['REQUEST_URI'];
		if(is_int(strpos($url, 'language'))) {
			$url = explode("language", $url);
			$url = $url[0];
			$url .= 'language=' . $code;
		} else {
			if(is_int(strpos($url, '?'))) {
				$url .= '&language=' . $code;
			} else {
				$url .= '?language=' . $code;
			}
		}
		return $url;
	}

	private function _setData($language_code, $path) {
		$file = $path . DIRECTORY_SEPARATOR . $language_code . '.data';
		if(!file_exists($file)) {
			$fp = fopen($file, "wb");
			fwrite($fp, '');
			fclose($fp);
		}
		$data = file_get_contents($file);
		$data = Json::decode($data);
		if(empty($data) || $data == null) {
			/**@var $models Phrase[] */
			$models = Phrase::find()->all();
			$code   = $language_code;
			foreach($models as $model) {
				$model->setDynamicField();
				$data[$model->name] = $model->$code;
			}
		} else {
			/**@var $models Phrase[] */
			$models = Phrase::find()->all();
			$code   = $language_code;
			foreach($models as $model) {
				$model->setDynamicField();
				if(!isset($data[$model->name])) {
					$data[$model->name] = $model->$code;
				}
			}
		}
		file_put_contents($file, Json::encode($data));
	}

	private function _setClass($path) {
		$php = '<?php' . PHP_EOL;
		$php .= 'namespace navatech\language;' . PHP_EOL;
		$php .= 'class Language {' . PHP_EOL;
		foreach($this->values as $key => $item) {
			$php .= '   public $' . $key . ';' . PHP_EOL;
		}
		$php .= '}';
		$file = $path . DIRECTORY_SEPARATOR . 'Language.php';
		$fp   = fopen($file, "wb");
		fwrite($fp, $php);
		fclose($fp);
	}

	public function setData($language_code = null) {
		$runtime = Yii::getAlias('@runtime');
		$path    = $runtime . DIRECTORY_SEPARATOR . 'language';
		if(!file_exists($path)) {
			mkdir($path, 0777, true);
		}
		if($language_code != null) {
			$this->_setData($language_code, $path);
		} else {
			foreach(LanguageModel::getAllLanguages() as $language) {
				$this->_setData($language->code, $path);
			}
		}
		$this->_setClass($path);
	}

	public function getData($language_code) {
		$runtime = Yii::getAlias('@runtime');
		$path    = $runtime . DIRECTORY_SEPARATOR . 'language';
		if(!file_exists($path)) {
			return array();
		}
		$file = $path . DIRECTORY_SEPARATOR . $language_code . '.data';
		if(!file_exists($file)) {
			return array();
		}
		$data = file_get_contents($file);
		$data = Json::decode($data);
		return $data;
	}
}