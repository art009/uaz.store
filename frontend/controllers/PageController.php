<?php
namespace frontend\controllers;

use common\models\Page;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * Page controller
 */
class PageController extends Controller
{
	/**
	 * Displays text pages
	 *
	 * @param int $id
	 *
	 * @return string
	 *
	 * @throws NotFoundHttpException
	 */
    public function actionView(int $id)
    {
    	$page = $this->findModel($id);

        return $this->render('view', [
        	'page' => $page,
        ]);
    }

	/**
	 * @param int $id
	 *
	 * @return Page
	 *
	 * @throws NotFoundHttpException
	 */
	protected function findModel(int $id)
	{
		$model = Page::findOne($id);
		if (!$model) {
			throw new NotFoundHttpException('Страница не найдена.');
		}
		return $model;
	}
}
