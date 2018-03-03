<?php

namespace frontend\widgets;

use Yii;
use yii\base\Widget;

/**
 * Class SeoTextWidget
 *
 * @package frontend\widgets
 */
class SeoTextWidget extends Widget
{
	const TYPE_MAIN = 'main';
	const TYPE_CATALOG = 'catalog';
	const TYPE_CATALOG_DVIGATEL = 'catalog-dvigatel';
	const TYPE_MANUAL_469 = 'manual-469';
	const TYPE_MANUAL_BUHANKA = 'manual-buhanka';
	const TYPE_MANUAL_HUNTER = 'manual-hunter';
	const TYPE_MANUAL_PATRIOT = 'manual-patriot';

	/**
	 * @var string
	 */
	protected $type = '';

	/**
	 * @return string
	 */
	public function getViewPath(): string
	{
		return parent::getViewPath() . '/seoText';
	}

	/**
	 * @return string
	 */
	public function getType(): string
	{
		return $this->type;
	}

	/**
	 * @param string $type
	 */
	public function setType(string $type)
	{
		$this->type = $type;
	}

	/**
	 * @throws \yii\base\InvalidConfigException
	 */
	public function init()
	{
		$request = Yii::$app->getRequest();
		switch ($request->getPathInfo()) {
			case '':
				$this->setType(self::TYPE_MAIN);
				break;
			case 'catalog':
				$this->setType(self::TYPE_CATALOG);
				break;
			case 'catalog/dvigatel':
				$this->setType(self::TYPE_CATALOG_DVIGATEL);
				break;
			case 'manual/2206':
				$this->setType(self::TYPE_MANUAL_BUHANKA);
				break;
			case 'manual/31519':
				$this->setType(self::TYPE_MANUAL_HUNTER);
				break;
			case 'manual/3163':
				$this->setType(self::TYPE_MANUAL_PATRIOT);
				break;
			default:
				break;
		}
	}

	/**
	 * @return string
	 */
	public function run()
	{
		$type = $this->getType();
		return $type ? $this->render($type) : null;
	}
}
