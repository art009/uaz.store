<?php

namespace common\behaviors;

use yii\base\Behavior;
use yii\db\ActiveRecord;
use yii\base\InvalidConfigException;

/**
 * Class SortableBehavior
 *
 * @package common\behaviors
 */
class SortableBehavior extends Behavior
{
    const EVENT_SORTABLE_UP = 'sortable_up';
    const EVENT_SORTABLE_DOWN = 'sortable_down';

    const ORDER_STEP = 10;

    /**
     * @var string
     */
    public $sortAttribute = 'sort_order';

	/**
	 * @var callable
	 */
    public $callback = null;

    /**
     * @inheritdoc
     */
    public function events()
    {
        return [
            ActiveRecord::EVENT_BEFORE_INSERT => 'beforeInsert',
            self::EVENT_SORTABLE_UP => 'up',
            self::EVENT_SORTABLE_DOWN => 'down',
        ];
    }

    /**
     * Возвращает модель
     *
     * @return ActiveRecord
     *
     * @throws InvalidConfigException
     */
    protected function getModel()
    {
        /** @var ActiveRecord $model */
        $model = $this->owner;
        if (!$model->hasAttribute($this->sortAttribute)) {
            throw new InvalidConfigException("Model don't have sortable attribute `{$this->sortAttribute}`.");
        }

        return $model;
    }

    /**
     * Установка порядка сортировки
     *
     * @throws InvalidConfigException
     */
    public function beforeInsert()
    {
        $model = $this->getModel();
        $model->{$this->sortAttribute} = $model::find()->max($this->sortAttribute) + self::ORDER_STEP;
    }

    /**
     * Перемещение элемента вверх
     */
    public function up()
    {
        $model = $this->getModel();

        $previous = $model::find()
            ->where(['<', $this->sortAttribute, $model->{$this->sortAttribute}])
            ->orderBy($this->sortAttribute . ' DESC')
            ->one();

        if ($previous) {
            $sortOrder = $previous->{$this->sortAttribute};
            $previous->updateAttributes([$this->sortAttribute => $model->{$this->sortAttribute}]);
            $model->updateAttributes([$this->sortAttribute => $sortOrder]);
        }

	    if (is_callable($this->callback)) {
		    call_user_func($this->callback);
	    }
    }

    /**
     * Перемещение элемента вниз
     */
    public function down()
    {
        $model = $this->getModel();

        $next = $model::find()
            ->where(['>', $this->sortAttribute, $model->{$this->sortAttribute}])
            ->orderBy($this->sortAttribute)
            ->one();

        if ($next) {
            $sortOrder = $next->{$this->sortAttribute};
            $next->updateAttributes([$this->sortAttribute => $model->{$this->sortAttribute}]);
            $model->updateAttributes([$this->sortAttribute => $sortOrder]);
        }

        if (is_callable($this->callback)) {
	        call_user_func($this->callback);
        }
    }
}
