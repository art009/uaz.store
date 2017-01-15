<?php

namespace common\actions;

use common\behaviors\SortableBehavior;
use yii\base\Action;
use yii\web\NotFoundHttpException;

/**
 * Class SortableAction
 *
 * @package common\actions
 */
class SortableAction extends Action
{
    const DIRECTION_UP = 'up';
    const DIRECTION_DOWN = 'down';

    const DEFAULT_NAME = 'sort';

    /**
     * @var string|null
     */
    public $modelName = null;

    /**
     * @param int $id
     * @param string $direction
     *
     * @return \yii\web\Response
     */
    public function run($id, $direction = self::DIRECTION_UP)
    {
        $model = $this->findModel($id);
        $model->trigger($direction == self::DIRECTION_DOWN ? SortableBehavior::EVENT_SORTABLE_DOWN : SortableBehavior::EVENT_SORTABLE_UP);

        return $this->controller->redirect([$this->controller->defaultAction]);
    }

    /**
     * @param $id
     * @return null|string|\yii\db\ActiveRecord
     *
     * @throws NotFoundHttpException
     */
    protected function findModel($id)
    {
        $model = $this->modelName;
        /** @var \yii\db\ActiveRecord $model */
        if (($model = $model::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('Model not found.');
        }
    }
}
