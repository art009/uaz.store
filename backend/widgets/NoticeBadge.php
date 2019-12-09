<?php

namespace backend\widgets;

use Yii;
use yii\base\Widget;
use backend\models\Notice;

/**
 * Display count unread notice (in backend)
 */
class NoticeBadge extends Widget
{
    /** @var int */
    public $count = 0;

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
    }

    /**
     * @inheritdoc
     */
    public function beforeRun()
    {
        $this->count = Notice::find()
            ->where(['status' => Notice::STATUS_NEW])
            ->count();

        return parent::beforeRun();
    }

    /**
     * @inheritdoc
     */
    public function run()
    {
        echo $this->count > 0 ? 'Уведомления <span class="badge">'.$this->count.'</span>' : '';
    }
}
