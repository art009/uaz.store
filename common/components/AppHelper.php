<?php

namespace common\components;

/**
 * Class AppHelper
 *
 * @package common\components
 */
class AppHelper
{
    const HIDDEN_NO = 0;
    const HIDDEN_YES = 1;

    /**
     * @var array
     */
    static $hiddenList = [
        self::HIDDEN_NO => 'Нет',
        self::HIDDEN_YES => 'Да',
    ];
}
