<?php

namespace frontend\controllers;

use frontend\models\CallbackForm;
use frontend\models\QuestionForm;
use yii\web\Controller;
use Yii;
use yii\web\Response;

/**
 * Class FormController
 *
 * @package frontend\controllers
 */
class FormController extends Controller
{
	/**
	 * Форма обратного звонка
	 *
	 * @return string
	 */
	public function actionCallback()
	{
		$model = new CallbackForm();

		$result = [
			'errors' => null,
		];
		if (Yii::$app->request->post($model->formName()) && $model->load(Yii::$app->request->post())) {
			if ($model->callback())	{
				$result['success'] = 'Обратный звонок успешно заказан.';
			} else {
				$result['errors'] = $model->firstErrors;
			}
		}

		if (Yii::$app->request->isAjax) {
			$response = new Response();
			$response->data = $result;
			$response->format = Response::FORMAT_JSON;
			return Yii::$app->end(0, $response);
		} else {
			return $this->renderPartial('callback', ['model' => $model]);
		}
	}

	/**
	 * Форма вопроса
	 *
	 * @return string
	 */
	public function actionQuestion()
	{
		$model = new QuestionForm();

		$result = [
			'errors' => null,
		];
		if (Yii::$app->request->post($model->formName()) && $model->load(Yii::$app->request->post())) {
			if ($model->send())	{
				$result['success'] = 'Ваш вопрос успешно отправлен.';
			} else {
				$result['errors'] = $model->firstErrors;
			}
		}

		if (Yii::$app->request->isAjax) {
			$response = new Response();
			$response->data = $result;
			$response->format = Response::FORMAT_JSON;
			return Yii::$app->end(0, $response);
		} else {
			return $this->renderPartial('question', ['model' => $model]);
		}
	}
}
