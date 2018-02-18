<?php

namespace common\components\cashbox;


/**
 * Class Cashbox
 * @package common\components\cashbox
 */
class Cashbox extends BaseCashbox
{
	/**
	 * @var int
	 */
	public $password = 1;

	/**
	 * Тип чека
	 *
	 * @var int
	 */
	public $documentType = 0;

	/**
	 * Применяемая в чеке система налогообложения.
	 * По умолчанию та, которая задана при регистрации.
	 *
	 * @var int|null
	 */
	public $taxMode;

	/**
	 * Место расчётов.
	 * По умолчанию то, которое задано при регистрации
	 *
	 * @var string|null
	 */
	public $place;

	/**
	 * @var bool
	 */
	public $fullResponse = false;

	/**
	 * Максимальное количество документов в одной смене.
	 *
	 * @var int
	 */
	public $maxDocumentsInTurn = 8000;

	/**
	 * Признак способа расчёта (1 - 7)
	 *
	 * @var int
	 */
	public $payAttribute = 4;

	/**
	 * Код налога (1 – 6)
	 *
	 * @var int
	 */
	public $taxId = 1;


	/**
	 * Initializes the object
	 * @return void
	 */
	public function init()
	{
		parent::init();

		$this->data['Device'] = 'auto';
		$this->data['RequestId'] = uniqid();
		$this->data['Password'] = $this->password;
		$this->data['DocumentType'] = $this->documentType;
		$this->data['FullResponse'] = $this->fullResponse;
		$this->data['MaxDocumentsInTurn'] = $this->maxDocumentsInTurn;

		if (!empty($this->taxMode)) {
			$this->data['TaxMode'] = $this->taxMode;
		}

		if (!empty($this->place)) {
			$this->data['Place'] = $this->place;
		}
	}

	/**
	 * Execute request
	 * @return bool|int Return bool or error code
	 */
	public function execute()
	{
		$this->setNonCash();
		$this->doRequest();

		$response = $this->getResponse();
		if (is_object($response) and isset($response->Response->Error)) {

			$error = $response->Response->Error;
			if ($error === 0) {
				return true;
			} else {
				return $error;
			}
		}

		return false;
	}

	/**
	 * Add product
	 *
	 * @param float $price Price in rubles
	 * @param int $count
	 * @param string $description
	 * @return $this
	 */
	public function setProduct(float $price, int $count, string $description)
	{
		$this->data['Lines'][] = [
			"Qty" => self::formatCount($count),
			"Price" => self::formatPrice($price),
			"PayAttribute" => $this->payAttribute,
			"TaxId" => $this->taxId,
			"Description"=> $description,
		];

		return $this;
	}

	/**
	 * Add phone or email
	 * @param string $value
	 * @return $this
	 */
	public function setPhoneOrEmail(string $value)
	{
		$this->data['PhoneOrEmail'] = $value;

		return $this;
	}

	/**
	 * Set NonCash attribute
	 * @return void
	 */
	protected function setNonCash()
	{
		$total = 0;
		foreach ($this->data['Lines'] as $line) {
			$total += ($line['Price'] * $line['Qty'] / self::getCountFactor());
		}

		$this->data['NonCash'] = [$total];
	}
}