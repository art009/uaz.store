<?php

namespace common\components;

use common\actions\SortableAction;
use yii\grid\ActionColumn;
use Yii;
use yii\helpers\Html;

/**
 * Class SortableActionColumn
 * @package common\components
 */
class SortableActionColumn extends ActionColumn
{
    /**
     * @inheritdoc
     */
    public $template = '{down} {up}';

    /**
     * @inheritdoc
     */
    protected function initDefaultButtons()
    {
        if (!isset($this->buttons['down'])) {
            $this->buttons['down'] = function ($url, $model, $key) {

                $options = array_merge([
                    'title' => Yii::t('yii', 'Down'),
                    'aria-label' => Yii::t('yii', 'Down'),
                    'data-pjax' => '0',
                ], $this->buttonOptions);

                $url = [SortableAction::DEFAULT_NAME, 'id' => $model->id, 'direction' => SortableAction::DIRECTION_DOWN];

                return Html::a('<span class="glyphicon glyphicon-arrow-down"></span>', $url, $options);
            };
        }

        if (!isset($this->buttons['up'])) {
            $this->buttons['up'] = function ($url, $model, $key) {

                $options = array_merge([
                    'title' => Yii::t('yii', 'Up'),
                    'aria-label' => Yii::t('yii', 'Up'),
                    'data-pjax' => '0',
                ], $this->buttonOptions);

                $url = [SortableAction::DEFAULT_NAME, 'id' => $model->id, 'direction' => SortableAction::DIRECTION_UP];

                return Html::a('<span class="glyphicon glyphicon-arrow-up"></span>', $url, $options);
            };
        }
    }
}
