<?php

namespace backend\modules\pms\components;

/**
 * Class ProviderItemAcceptCache
 *
 * @package backend\modules\pms\components
 */
class ProviderItemAcceptCache
{
	/**
	 * @var int
	 */
	protected $providerId;

	/**
	 * ProviderItemAcceptCache constructor.
	 *
	 * @param int $providerId
	 */
	public function __construct(int $providerId)
	{
		$this->providerId = $providerId;
	}

	/**
	 * @return string
	 */
	protected function getKey()
	{
		return md5(__FILE__ . $this->providerId);
	}

	/**
	 * @return \yii\redis\Cache
	 */
	protected function getCache()
	{
		return \Yii::$app->cache;
	}

	/**
	 * Существование кеша для поставщика
	 *
	 * @return bool
	 */
	public function exists(): bool
	{
		$key = $this->getKey();
		$cache = $this->getCache();

		return $cache->exists($key);
	}

	/**
	 * Помещает данные в кеш
	 *
	 * @param array $data
	 */
	public function set(array $data)
	{
		$key = $this->getKey();
		$cache = $this->getCache();

		$cache->set($key, json_encode($data));
	}

	/**
	 * Достает данные из кеша
	 *
	 * @return array
	 */
	public function get(): array
	{
		$result = [];
		$key = $this->getKey();
		$cache = $this->getCache();

		if ($this->exists()) {
			$json = $cache->get($key);
			$result = json_decode($json, true);
		}

		return $result;
	}

	/**
	 * @return bool
	 */
	public function clear(): bool
	{
		$key = $this->getKey();
		$cache = $this->getCache();

		return $cache->delete($key);
	}
}
