<?php
namespace frontend\controllers;

use common\models\CatalogProduct;
use Yii;
use yii\base\InvalidParamException;
use yii\db\Exception;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\LoginForm;
use frontend\models\PasswordResetRequestForm;
use frontend\models\ResetPasswordForm;
use frontend\models\SignupForm;
use frontend\models\ContactForm;
use yii\web\NotFoundHttpException;

/**
 * Site controller
 */
class SiteController extends Controller
{
    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

	public function actionSearch($q = null)
	{
		$products = [];
		$emptyString = 'Товары не найдены.';
		try
		{
			$query = new \yii\sphinx\Query();
			$ids = $query->select('id')
				->from('usp')
				->match(new \yii\sphinx\MatchExpression(':q', ['q' => $q]))
				->limit(100)
				->all();

			if($ids)
				$products = CatalogProduct::findAll($ids);
		}
		catch (Exception $e)
		{
			$emptyString = 'Поиск товара недоступен.';
		}

		return $this->render('search', [
			'products' => $products,
			'query' => $q,
			'emptyString' => $emptyString,
		]);
    }
}
