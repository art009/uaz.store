<?php

namespace common\classes;

/**
 * Class TreeItem
 *
 * @package common\classes
 */
class TreeItem
{
	public $id;
	public $parentId;
	public $title;
	public $link;
	public $active = false;
	public $expanded = false;
	public $items = [];

	/**
	 * TreeItem constructor
	 *
	 * @param array $params
	 */
	public function __construct(array $params)
	{
		$this->id = $params['id'] ?? null;
		$this->parentId = $params['parent_id'] ?? null;
		$this->title = $params['title'] ?? null;
		$this->link = $params['link'] ?? null;
	}

	/**
	 * @return bool
	 */
	public function isValid()
	{
		return !empty($this->id) && !empty($this->title);
	}

	/**
	 * @param TreeItem $item
	 */
	public function addChild(TreeItem $item)
	{
		if ($item->isValid()) {
			$item->updateLink($this->link . '/');
			$this->items[$item->id] = $item;
			$this->expanded |= $item->active;
		}
	}

	/**
	 * @param string $prefix
	 */
	public function updateLink($prefix)
	{
		if ($this->link && $prefix) {
			$this->link = $prefix . $this->link;
		}
	}
}
