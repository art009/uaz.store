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
    const HIDDEN_NO = 0;
    const HIDDEN_YES = 1;

    const UPLOADS_FOLDER = 'uploads';

    /**
     * @var array
     */
    static $hiddenList = [
        self::HIDDEN_NO => 'Нет',
        self::HIDDEN_YES => 'Да',
    ];

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
            'Ы' => 'E', 'ы' => 'e',
            'Ь' => '', 'ь' => '',
            'Э' => 'E', 'э' => 'e',
            'Ю' => 'Ju', 'ю' => 'ju',
            'Я' => 'Ja', 'я' => 'ja',
            'і' => 'i', 'є' => 'je',
            'ї' => 'ji', 'ґ' => 'g',
        );

        $str = strtr($str, $transliteration);
        $str = mb_strtolower($str, 'UTF-8');
        $str = preg_replace('/[^0-9a-z\-\s]/', '', $str);
        $str = preg_replace('|([-]+)|s', '-', $str);
        $str = preg_replace('/\s/', '-', $str);
        $str = trim($str, '-');

        return $str;
    }
}
