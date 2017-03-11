<?php

namespace frontend\controllers;

use common\models\LoginForm;
use common\models\User;
use frontend\models\PasswordResetRequestForm;
use frontend\models\ResetPasswordForm;
use frontend\models\SignupForm;
use Yii;
use yii\base\InvalidParamException;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\BadRequestHttpException;
use yii\web\Controller;

/**
 * Class UserController
 *
 * @package frontend\controllers
 */
class UserController extends Controller
{
	/**
	 * @inheritdoc
	 */
	public function behaviors()
	{
		return [
			'access' => [
				'class' => AccessControl::className(),
				'only' => ['index', 'logout', 'signup', 'password-reset', 'set-password'],
				'rules' => [
					[
						'actions' => ['signup', 'password-reset', 'set-password'],
						'allow' => true,
						'roles' => ['?'],
					],
					[
						'actions' => ['index', 'logout'],
						'allow' => true,
						'roles' => ['@'],
					],
				],
			],
			'verbs' => [
				'class' => VerbFilter::className(),
				'actions' => [
					'logout' => ['post'],
				],
			],
		];
	}

	/**
	 * Страница ЛК
	 *
	 * @return string
	 */
	public function actionIndex()
	{
		return $this->render('index');
	}

	/**
	 * Авторизация
	 *
	 * @return mixed
	 */
	public function actionLogin()
	{
		if (!Yii::$app->user->isGuest) {
			return $this->goHome();
		}

		$model = new LoginForm();
		if ($model->load(Yii::$app->request->post()) && $model->login()) {
			return $this->goBack();
		} else {
			return $this->render('login', [
				'model' => $model,
			]);
		}
	}

	/**
	 * Разлогинивание
	 *
	 * @return mixed
	 */
	public function actionLogout()
	{
		Yii::$app->user->logout();

		return $this->goHome();
	}

	/**
	 * Регистрация
	 *
	 * @return mixed
	 */
	public function actionSignup()
	{
		$model = new SignupForm();
		$model->legal = User::LEGAL_NO;
		if ($model->load(Yii::$app->request->post())) {
			if ($user = $model->signup()) {
				Yii::$app->session->setFlash('success', 'Вы успешно зарегистрировались на сайте.');
				if (Yii::$app->getUser()->login($user)) {
					return $this->redirect(['/user']);
				}
			}
		}

		return $this->render('signup', [
			'model' => $model,
		]);
	}

	/**
	 * Сброс пароля
	 *
	 * @return mixed
	 */
	public function actionPasswordReset()
	{
		$model = new PasswordResetRequestForm();
		if ($model->load(Yii::$app->request->post()) && $model->validate()) {
			if ($model->sendEmail()) {
				Yii::$app->session->setFlash('success', 'Вам отправлено <b>письмо</b> с инструкциями по восстановлению доступа.');
				return $this->redirect(['/login']);
			} else {
				Yii::$app->session->setFlash('error', 'В данный момент вы не можете сбросить пароль для указанного E-mail');
			}
		}

		return $this->render('resetPassword', [
			'model' => $model,
		]);
	}

	/**
	 * Установка пароля
	 *
	 * @param string $token
	 * @return mixed
	 * @throws BadRequestHttpException
	 */
	public function actionSetPassword($token)
	{
		try {
			$model = new ResetPasswordForm($token);
		} catch (InvalidParamException $e) {
			throw new BadRequestHttpException($e->getMessage());
		}

		if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) {
			Yii::$app->session->setFlash('success', 'Новый пароль <b>успешно</b> установлен.');
			return $this->redirect(['/login']);
		}

		return $this->render('setPassword', [
			'model' => $model,
		]);
	}
}
