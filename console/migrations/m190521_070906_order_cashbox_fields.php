<?php

use yii\db\Migration;

/**
 * Class m190521_070906_order_cashbox_fields
 */
class m190521_070906_order_cashbox_fields extends Migration
{
	/**
	 * @inheritdoc
	 */
	public function safeUp()
	{
		$this->addColumn('order', 'cash_box_sent_at', $this->timestamp()->defaultValue('0000-00-00 00:00:00')->null()->comment('Время отправки чека в кассу')->after('payment_id'));
		$this->addColumn('order', 'cash_box_sent_error', $this->text()->null()->comment('Ошибка отправки чека в кассу')->after('cash_box_sent_at'));
		$this->addColumn('order', 'cash_box_return_at', $this->timestamp()->defaultValue('0000-00-00 00:00:00')->null()->comment('Время отправки возврата в кассу')->after('cash_box_sent_error'));
		$this->addColumn('order', 'cash_box_return_error', $this->text()->null()->comment('Ошибка отправки возврата в кассу')->after('cash_box_return_at'));
	}

	/**
	 * @inheritdoc
	 */
	public function safeDown()
	{
		$this->dropColumn('order', 'cash_box_sent_at');
		$this->dropColumn('order', 'cash_box_sent_error');
		$this->dropColumn('order', 'cash_box_return_at');
		$this->dropColumn('order', 'cash_box_return_error');
	}
}
