<?php
namespace frontend\controllers;

use common\components\AppHelper;
use common\models\News;
use yii\data\Pagination;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * News controller
 */
class NewsController extends Controller
{
    /**
     * News page
     *
     * @return string
     */
    public function actionIndex()
    {
        $query = News::find()
            ->where(['hide' => AppHelper::NO])
            ->orderBy(['created_at' => SORT_DESC]);
        $pages = new Pagination(['totalCount' => $query->count()]);
        $models = $query->offset($pages->offset)
            ->limit($pages->limit)
            ->all();

        return $this->render('index', [
            'models' => $models,
            'pages' => $pages,
        ]);
    }

	/**
	 * Displays text news
	 *
	 * @param int $id
	 *
	 * @return string
	 *
	 * @throws NotFoundHttpException
	 */
    public function actionView(int $id)
    {
    	$news = $this->findModel($id);

        return $this->render('view', [
        	'news' => $news,
        ]);
    }

	/**
	 * @param int $id
	 *
	 * @return News
	 *
	 * @throws NotFoundHttpException
	 */
	protected function findModel(int $id)
	{
		$model = News::findOne($id);
		if (!$model) {
			throw new NotFoundHttpException('Страница не найдена.');
		}

		return $model;
	}
}
