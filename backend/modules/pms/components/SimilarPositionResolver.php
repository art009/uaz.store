<?php

namespace backend\modules\pms\components;

use app\modules\pms\models\ProviderItem;

/**
 * Class SimilarPositionResolver
 *
 * @package backend\modules\pms\components
 */
class SimilarPositionResolver
{
	const SIMILAR_TEXT_PERCENT = 55;
	const SIMILAR_TEXT_ADDITIONAL_PERCENT = 80;

	/**
	 * @var string|null
	 */
	protected $query = null;

	/**
	 * @var string|null
	 */
	protected $additionalQuery = null;

	/**
	 * @var int
	 */
	protected $providerId;

	/**
	 * SimilarPositionResolver constructor.
	 *
	 * @param int $providerId
	 * @param string $query
	 * @param string|null $additionalQuery
	 */
	public function __construct(int $providerId, string $query, string $additionalQuery = null)
	{
		$this->providerId = $providerId;
		$this->query = $query;
		$this->additionalQuery = $additionalQuery ?: $query;
	}

	/**
	 * @return array
	 */
	protected function getItems()
	{
		$providerId = $this->providerId;

		$result = ProviderItem::getDb()->cache(function () use ($providerId) {
			return ProviderItem::find()
				->select(['id', 'title', 'vendor_code'])
				->where(['provider_id' => $providerId])
				->asArray()
				->all();
		}, 1800);

		return $result;
	}

	/**
	 * @return array
	 */
	public function getIds()
	{
		$result = [];
		$items = $this->getItems();
		foreach ($items as $item) {
			if ($this->checkBySimilarText($item['title']) || $this->checkBySimilarText($item['vendor_code'], true)) {
				$result[] = $item['id'];
			}
		}

		return $result;
	}

	/**
	 * @param string $text
	 * @param bool $additional
	 *
	 * @return bool
	 */
	protected function checkBySimilarText(string $text, bool $additional = false)
	{
		similar_text($text, $additional ? $this->additionalQuery : $this->query, $percent);
		return $percent >= ($additional ? self::SIMILAR_TEXT_ADDITIONAL_PERCENT : self::SIMILAR_TEXT_PERCENT);
	}
}
