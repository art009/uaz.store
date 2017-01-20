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
}
