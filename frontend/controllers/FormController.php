<?php

namespace frontend\controllers;

use frontend\models\CallbackForm;
use frontend\models\OrderForm;
use frontend\models\QuestionForm;
use Yii;
use yii\web\Controller;
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
	 *
	 * @throws \yii\base\ExitException
	 */
	public function actionCallback()
	{
		return $this->processForm(new CallbackForm(), 'Обратный звонок успешно заказан.', 'callback');
	}

	/**
	 * Форма вопроса
	 *
	 * @return string
	 *
	 * @throws \yii\base\ExitException
	 */
	public function actionQuestion()
	{
		return $this->processForm(new QuestionForm(), 'Ваш вопрос успешно отправлен.', 'question');
	}

	/**
	 * Форма быстрого заказа
	 *
	 * @return string
	 *
	 * @throws \yii\base\ExitException
	 */
	public function actionOrder()
	{
		return $this->processForm(new OrderForm(), 'Ваш запрос успешно отправлен.', 'order');
	}

	/**
	 * @param CallbackForm|QuestionForm|OrderForm $model
	 * @param string $successText
	 * @param string $viewName
	 *
	 * @return string
	 *
	 * @throws \yii\base\ExitException
	 */
	protected function processForm($model, string $successText, string $viewName)
	{
		$result = [
			'errors' => null,
		];
		if (Yii::$app->request->post($model->formName()) && $model->load(Yii::$app->request->post())) {
			if ($model->create())	{
				$result['success'] = $successText;
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
			return $this->renderPartial($viewName, ['model' => $model]);
		}
	}
}
