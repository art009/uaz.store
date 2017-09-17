<?php

namespace common\components;

use Yii;

/**
 * Class AppHelper
 *
 * @package common\components
 */
class AppHelper
{
    const NO = 0;
    const YES = 1;

    const UPLOADS_FOLDER = 'uploads';

    /**
     * @var array
     */
    static $yesNoList = [
        self::NO => 'Нет',
        self::YES => 'Да',
    ];

	/**
	 * @var array
	 */
    public static $transliterationLinks = [];

    /**
     * Путь к папке картинок
     *
     * @param string $env
     *
     * @return string
     */
    public static function uploadsFolder($env = 'frontend')
    {
        return Yii::getAlias('@' . $env) . '/web/' . self::UPLOADS_FOLDER;
    }

    /**
     * Урл к папке картинок
     *
     * @param string $env
     *
     * @return string
     */
    public static function uploadsPath($env = 'frontend')
    {
        return Yii::$app->params[$env . 'Url'] . self::UPLOADS_FOLDER;
    }

    /**
     * Пуль до картинки-водяного знака (120x64)
     *
     * @return string
     */
    public static function watermarkFile()
    {
        return Yii::getAlias('@backend') . '/web/img/watermark.png';
    }

    /**
     * Транслитерация
     *
     * @param string $str
     *
     * @return mixed|string
     */
    public static function transliteration($str)
    {
        $transliteration = array(
            'А' => 'A', 'а' => 'a',
            'Б' => 'B', 'б' => 'b',
            'В' => 'V', 'в' => 'v',
            'Г' => 'G', 'г' => 'g',
            'Д' => 'D', 'д' => 'd',
            'Е' => 'E', 'е' => 'e',
            'Ё' => 'E', 'ё' => 'e',
            'Ж' => 'Zh', 'ж' => 'zh',
            'З' => 'Z', 'з' => 'z',
            'И' => 'I', 'и' => 'i',
            'Й' => 'J', 'й' => 'j',
            'К' => 'K', 'к' => 'k',
            'Л' => 'L', 'л' => 'l',
            'М' => 'M', 'м' => 'm',
            'Н' => "N", 'н' => 'n',
            'О' => 'O', 'о' => 'o',
            'П' => 'P', 'п' => 'p',
            'Р' => 'R', 'р' => 'r',
            'С' => 'S', 'с' => 's',
            'Т' => 'T', 'т' => 't',
            'У' => 'U', 'у' => 'u',
            'Ф' => 'F', 'ф' => 'f',
            'Х' => 'H', 'х' => 'h',
            'Ц' => 'Ts', 'ц' => 'ts',
            'Ч' => 'Ch', 'ч' => 'ch',
            'Ш' => 'Sh', 'ш' => 'sh',
            'Щ' => 'Sch', 'щ' => 'sch',
            'Ъ' => '', 'ъ' => '',
            'Ы' => 'Y', 'ы' => 'y',
            'Ь' => '', 'ь' => '',
            'Э' => 'E', 'э' => 'e',
            'Ю' => 'Ju', 'ю' => 'ju',
            'Я' => 'Ya', 'я' => 'ya',
            'і' => 'i', 'є' => 'je',
            'ї' => 'ji', 'ґ' => 'g',
        );

        $str = strtr($str, $transliteration);
        $str = mb_strtolower($str, 'UTF-8');
        $str = preg_replace('/[^0-9a-z\-\s\/]/', '', $str);
        $str = preg_replace('|([-]+)|s', '-', $str);
        $str = preg_replace('/[\s\/]/', '-', $str);
        $str = trim($str, '-');
        if (array_key_exists($str, self::$transliterationLinks)) {
	        self::$transliterationLinks[$str]++;
	        $str .= '-' . self::$transliterationLinks[$str];
        } else {
	        self::$transliterationLinks[$str] = 1;
        }

        return $str;
    }

	/**
	 * Загрузка данных из JSON-файла
	 *
	 * @param string $path
	 *
	 * @return mixed|null
	 */
	public static function loadFromJsonFile($path)
	{
		$result = null;
		if (file_exists($path)) {
			$content = @file_get_contents($path);
			$json = json_decode($content, true);
			if (json_last_error() == JSON_ERROR_NONE) {
				$result = $json;
			}
		}

		return $result;
	}

	/**
	 * @param $image
	 * @param $folder
	 * @return string
	 */
	public static function getImagePath($image, $folder)
	{
		if ($image && file_exists(self::uploadsFolder() . '/' . $folder . '/' . $image)) {
			return self::uploadsPath() . '/' . $folder . '/' . $image;
		} else {
			return '/img/empty-s.png';
		}
	}
}
