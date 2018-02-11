<?php

namespace common\components;

use yii\base\Component;
use yii\sphinx\MatchExpression;
use yii\sphinx\Query;

/**
 * Class SphinxSearch
 *
 * @package common\components
 */
class SphinxSearch extends Component
{
	/**
	 * @var string
	 */
	public $index;

	/**
	 * @var int
	 */
	public $limit = 300;

	/**
	 * @param string $expression
	 *
	 * @return int[]
	 */
	public function getIds(string $expression): array
	{
		$query = new Query();

		$query->select('id');
		$query->from($this->index);
		$query->match(new MatchExpression(':q', ['q' => $expression]));
		$query->limit($this->limit);

		return $query->all();
	}
}
