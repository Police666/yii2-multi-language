<?php
/**
 * Created by Navatech.
 * @project    Yii2 Multi Language
 * @author     Phuong
 * @email      phuong17889[at]gmail.com
 * @created    14/02/2016 4:25 CH
 * @updated    03/03/2016 00:40 SA
 * @since      2.0.0
 */
namespace navatech\language\helpers;

use navatech\language\models\Language;
use navatech\language\models\Phrase;
use navatech\language\models\PhraseTranslate;
use Yii;
use yii\base\ErrorException;
use yii\base\Exception;
use yii\base\InvalidParamException;
use yii\helpers\Json;

class MultiLanguageHelpers {

	/**
	 * @param $name
	 *
	 * @return string
	 * @since 1.0.2
	 */
	public static function newPhrase($name) {
		$model       = new Phrase();
		$model->name = $name;
		if ($model->save()) {
			$PhraseTranslate              = new PhraseTranslate();
			$PhraseTranslate->phrase_id   = $model->getPrimaryKey();
			$PhraseTranslate->language_id = Language::getIdByCode(Yii::$app->language);
			$PhraseTranslate->value       = 'error: phrase [' . $name . '] not found';
			$PhraseTranslate->save();
		}
		return 'error: phrase [' . $name . '] not found';
	}

	/**
	 * @param $language_code
	 * @param $path
	 *
	 * @return array
	 * @since 1.0.0
	 * @throws InvalidParamException
	 */
	private static function _setAllData($language_code, $path) {
		$file = $path . DIRECTORY_SEPARATOR . 'phrase_' . $language_code . '.json';
		if (!file_exists($file)) {
			$fp = fopen($file, 'wb');
			fwrite($fp, '');
			fclose($fp);
		}
		try {
			$myFile = fopen($file, "r");
			$data   = fread($myFile, filesize($file));
		} catch (ErrorException $e) {
			throw new ErrorException('Unable to open "' . $file . '""');
		}
		fclose($myFile);
		if ($data === '') {
			/**@var $models Phrase[] */
			$models = Phrase::find()->all();
			$code   = $language_code;
			foreach ($models as $model) {
				$model->setDynamicField();
				$data[$model->name] = $model->$code;
			}
		} else {
			$data = [];
			/**@var $models Phrase[] */
			$models = Phrase::find()->all();
			$code   = $language_code;
			foreach ($models as $model) {
				$model->setDynamicField();
				if (!array_key_exists($model->name, $data)) {
					$data[$model->name] = $model->$code;
				}
			}
		}
		file_put_contents($file, Json::encode($data));
		return $data;
	}

	/**
	 * @param $path
	 *
	 * @param $data
	 *
	 * @since 1.0.0
	 */
	private static function _setClass($path, $data) {
		$php = '<?php' . PHP_EOL;
		$php .= 'namespace navatech\language;' . PHP_EOL;
		$php .= 'class Translate {' . PHP_EOL;
		foreach ($data as $key => $item) {
			$php .= '       /**' . PHP_EOL;
			$php .= '       * @param null|array|mixed $parameters' . PHP_EOL;
			$php .= '       * @param null|string $language_code' . PHP_EOL;
			$php .= '       * @return string' . PHP_EOL;
			$php .= '       */' . PHP_EOL;
			$php .= '       public static function ' . $key . '($parameters = null, $language_code = null){}' . PHP_EOL;
		}
		$php .= '//defined_new_method_here' . PHP_EOL;
		$php .= '}';
		$file = $path . DIRECTORY_SEPARATOR . 'Translate.php';
		$fp   = fopen($file, 'wb');
		fwrite($fp, $php);
		fclose($fp);
	}

	/**
	 *
	 * @throws Exception|InvalidParamException
	 */
	public static function setLanguages() {
		$runtime = Yii::getAlias('@runtime');
		$path    = $runtime . DIRECTORY_SEPARATOR . 'language';
		if (!file_exists($path) && !@mkdir($path, 0777, true) && !is_dir($path)) {
			throw new Exception('Cannot create directory');
		}
		$code = [];
		foreach (Language::getAllLanguages() as $language) {
			$code[$language->id] = $language->getAttributes();
		}
		$file = $path . DIRECTORY_SEPARATOR . 'languages.json';
		$fp   = fopen($file, 'wb');
		fwrite($fp, Json::encode($code));
		fclose($fp);
	}

	/**
	 *
	 * @return array|mixed
	 * @since 1.0.2
	 * @throws Exception|InvalidParamException
	 */
	public static function getLanguages() {
		$runtime = Yii::getAlias('@runtime');
		$path    = $runtime . DIRECTORY_SEPARATOR . 'language';
		if (!file_exists($path)) {
			self::setLanguages();
			return self::getLanguages();
		}
		$file = $path . DIRECTORY_SEPARATOR . 'languages.json';
		if (!file_exists($file)) {
			self::setLanguages();
			return self::getLanguages();
		}
		try {
			$myFile = fopen($file, "r");
			$data   = fread($myFile, filesize($file));
		} catch (ErrorException $e) {
			throw new ErrorException('Unable to open "' . $file . '""');
		}
		$data = Json::decode($data);
		fclose($myFile);
		return $data;
	}

	/**
	 * @param null $language_code
	 *
	 * @throws Exception|InvalidParamException
	 */
	public static function setAllData($language_code = null) {
		$runtime = Yii::getAlias('@runtime');
		$path    = $runtime . DIRECTORY_SEPARATOR . 'language';
		if (!file_exists($path) && !@mkdir($path, 0777, true) && !is_dir($path)) {
			throw new Exception('Cannot create directory');
		}
		$data = null;
		if ($language_code !== null) {
			$data = self::_setAllData($language_code, $path);
		} else {
			foreach (Language::getAllLanguages() as $language) {
				$data = self::_setAllData($language->code, $path);
			}
		}
		self::_setClass($path, $data);
	}

	/**
	 * @param $language_code
	 *
	 * @return array|mixed|string
	 * @since 1.0.1
	 * @throws InvalidParamException|ErrorException
	 */
	public static function getData($language_code) {
		$runtime = Yii::getAlias('@runtime');
		$path    = $runtime . DIRECTORY_SEPARATOR . 'language';
		$file    = $path . DIRECTORY_SEPARATOR . 'phrase_' . $language_code . '.json';
		if (!file_exists($path) || !file_exists($file)) {
			self::setAllData($language_code);
			return self::getData($language_code);
		}
		try {
			$myFile  = fopen($file, "r");
			$content = fread($myFile, filesize($file));
		} catch (ErrorException $e) {
			throw new ErrorException('Unable to open "' . $file . '""');
		}
		fclose($myFile);
		if ($content === '') {
			self::setAllData($language_code);
			return self::getData($language_code);
		}
		$data = Json::decode($content);
		if ($data === null) {
			self::setAllData($language_code);
			return self::getData($language_code);
		}
		return $data;
	}

	/**
	 * @param $language_code
	 *
	 * @return bool
	 * @since 1.0.0
	 * @throws InvalidParamException
	 */
	public static function removeAllData($language_code) {
		$runtime = Yii::getAlias('@runtime');
		$path    = $runtime . DIRECTORY_SEPARATOR . 'language';
		if (!file_exists($path)) {
			return true;
		}
		$file = $path . DIRECTORY_SEPARATOR . 'phrase_' . $language_code . '.json';
		if (!file_exists($file)) {
			return true;
		}
		return unlink($file);
	}

	/**
	 * @param PhraseTranslate $model
	 *
	 * @since 1.0.2
	 * @throws Exception|InvalidParamException
	 */
	public static function setData(PhraseTranslate $model) {
		$name          = $model->getPhrase()->name;
		$language_code = $model->getLanguage()->code;
		$runtime       = Yii::getAlias('@runtime');
		$path          = $runtime . DIRECTORY_SEPARATOR . 'language';
		if (!file_exists($path)) {
			self::setAllData();
		}
		if ($model->isNewRecord) {
			foreach (self::getLanguages() as $language) {
				$file = $path . DIRECTORY_SEPARATOR . 'phrase_' . $language['code'] . '.json';
				if (!file_exists($file)) {
					self::setAllData($language['code']);
				}
				try {
					$myFile = fopen($file, "r");
					$data   = fread($myFile, filesize($file));
				} catch (ErrorException $e) {
					throw new ErrorException('Unable to open "' . $file . '""');
				}
				fclose($myFile);
				$data = Json::decode($data);
				if ($data === null) {
					self::setAllData($language['code']);
				} else {
					if (!array_key_exists($name, $data)) {
						$data[$name] = $model->value;
						file_put_contents($file, Json::encode($data));
					}
				}
			}
			$class = $path . DIRECTORY_SEPARATOR . 'Translate.php';
			if (!file_exists($class)) {
				self::setAllData();
			}
			try {
				$myFile  = fopen($class, "r");
				$content = fread($myFile, filesize($class));
			} catch (ErrorException $e) {
				throw new ErrorException('Unable to open "' . $class . '""');
			}
			fclose($myFile);
			if (!strpos($content, 'function ' . $name . '(')) {
				$php = '       /**' . PHP_EOL;
				$php .= '       * @param null|array|mixed $parameters' . PHP_EOL;
				$php .= '       * @param null|string $language_code' . PHP_EOL;
				$php .= '       * @return string' . PHP_EOL;
				$php .= '       */' . PHP_EOL;
				$php .= '       public static function ' . $name . '($parameters = null, $language_code = null){}' . PHP_EOL;
				$php .= '//defined_new_method_here' . PHP_EOL;
				$content = str_replace('//defined_new_method_here', $php, $content);
				$fp      = fopen($class, 'wb');
				fwrite($fp, $content);
				fclose($fp);
			}
		} else {
			$file = $path . DIRECTORY_SEPARATOR . 'phrase_' . $language_code . '.json';
			if (!file_exists($file)) {
				self::setAllData($language_code);
			}
			try {
				$myFile = fopen($file, "r");
				$data   = fread($myFile, filesize($file));
			} catch (ErrorException $e) {
				throw new ErrorException('Unable to open "' . $file . '""');
			}
			$data = Json::decode($data);
			fclose($myFile);
			if ($data === null) {
				self::setAllData($language_code);
			} else {
				$data[$name] = $model->value;
				file_put_contents($file, Json::encode($data));
			}
		}
	}
}
