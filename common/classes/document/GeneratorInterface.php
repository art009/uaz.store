<?php

namespace common\classes\document;

/**
 * Interface GeneratorInterface
 *
 * @package common\classes\document
 */
interface GeneratorInterface
{
	/**
	 * @param string $path
	 */
	public function setFilePath(string $path);

	/**
	 * @param string $path
	 */
	public function setTemplatePath(string $path);

	/**
	 * @param array $data
	 */
	public function setTemplateData(array $data);

	/**
	 * Генерирует документ
	 *
	 * @return bool
	 */
	public function generate(): bool;
}
