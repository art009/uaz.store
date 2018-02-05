<?php

namespace console\controllers;

use Yii;
use yii\console\Controller;

/**
 * Class EmptyController
 *
 * @package console\controllers
 */
class EmptyController extends Controller
{
	public function actionIndex()
	{
	}

	/**
	 * @param string $q
	 */
	public function actionSphinx(string $q)
	{
		$query = new \yii\sphinx\Query();
		$rows = $query->select('id, title, shop_code')
			->from('usp')
			->match(new \yii\sphinx\MatchExpression('@title :title', ['title' => $q]))
			->all();

		echo print_r($rows, true);
	}
}
