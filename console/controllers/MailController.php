<?php

namespace console\controllers;

use common\models\MailQueue;
use Yii;
use yii\console\Controller;

/**
 * Class EmptyController
 *
 * @package console\controllers
 */
class MailController extends Controller
{
	public function actionSend()
	{
	    $countSent = MailQueue::send();
	    echo "Sent emails: $countSent\n";
	}
}
