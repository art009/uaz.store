<?php

namespace frontend\components;

use \rmrevin\yii\minify\View;

class SeoView extends View
{
    public $keywords;

    public function addSeoMetatags()
    {
        $this->keywords = $this->params['keywords'] ?? $this->params['breadcrumbs'] ?? [];
        $this->registerMetaTag([
            'name' => 'description',
            'content' => $this->params['description'] ?? 'Продажа запчастей для автомобилей УАЗ всех моделей с доставкой по всей России. У нас Вы сможете купить запчасти УАЗ по лучшим ценам.'
        ]);
        $originalKeywords = ['запчасти УАЗ', 'купить запчасти УАЗ', 'магазин запчастей УАЗ'];
        if (isset($this->keywords)) {
            if (is_scalar($this->keywords)) {
                $moreKeywords = [$this->keywords];
            } else {
                $moreKeywords = $this->keywords;
            }
            $processed = [];
            foreach ($moreKeywords as $keyword) {
                if (is_array($keyword) && (isset($keyword['label']))) {
                    $processed[] = $keyword['label']." УАЗ";
                } else {
                    $processed[] = $keyword." УАЗ";
                }
            }
            $originalKeywords = \yii\helpers\ArrayHelper::merge($originalKeywords, $processed);
        }
        $this->registerMetaTag([
            'name' => 'keywords',
            'content' => implode(', ', $originalKeywords)
        ]);
    }
}