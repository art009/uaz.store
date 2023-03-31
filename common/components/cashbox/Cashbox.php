<?php

namespace common\components\cashbox;

use common\models\Order;
use yii\base\Exception;

/**
 * Class Cashbox
 * @package common\components\cashbox
 */
class Cashbox extends BaseCashbox
{
	private const BASE_URL = 'http://94.181.181.64:4444/';

	/** Типы документов */
	public const DOCUMENT_TYPE_COMING = 0;
	public const DOCUMENT_TYPE_RETURN_COMING = 2;

	public $url = self::BASE_URL . 'fr/api/v2/Complex';

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
	public $taxId = 4;


	/**
	 * Initializes the object
	 * @return void
	 */
	public function init()
	{
		$this->data['Device'] = 'auto';
		$this->data['RequestId'] = uniqid();
		$this->data['Password'] = $this->password;
		$this->data['DocumentType'] = $this->documentType;
		$this->data['FullResponse'] = $this->fullResponse;
		$this->data['MaxDocumentsInTurn'] = $this->maxDocumentsInTurn;

		if ($this->taxMode) {
			$this->data['TaxMode'] = $this->taxMode;
		}

		if ($this->place) {
			$this->data['Place'] = $this->place;
		}
	}

	/**
	 * @return bool|int Return bool or error code
	 *
	 * @throws \yii\base\Exception
	 */
	public function execute()
	{
		$this->setNonCash();

		$response = $this->request('fr/api/v2/Complex', $this->data);
		\Yii::warning('RequestData:' . json_encode($this->data) . PHP_EOL . 'Response' . json_encode($response));
		if (is_array($response) && array_key_exists('Response', $response)) {
			$error = $response['Response']['Error'] ?? 0;
			if ($error === 0) {
				return true;
			} else {
				return $error . ' -> ' . implode(', ', $response['Response']['ErrorMessages'] ?? []);
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
     * Add delivery
     *
     * @param float $price Price in rubles
     * @param int $count
     * @param string $description
     * @return $this
     */
    public function setDelivery(Order $order)
    {
        $this->data['Lines'][] = [
            "Qty" => self::formatCount(1),
            "Price" => self::formatPrice($order->delivery_sum),
            "PayAttribute" => $this->payAttribute,
            "TaxId" => $this->taxId,
            "Description"=> 'Доставка',
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
			$total += (ceil($line['Price']) * $line['Qty'] / self::getCountFactor());
		}

		$this->data['NonCash'] = [$total];
	}

	public function applyReturnDocumentType(): void
	{
		$this->data['DocumentType'] = self::DOCUMENT_TYPE_RETURN_COMING;
	}

	/**
	 * @return array
	 *
	 * @throws Exception
	 */
	public function status(): array
	{
		return $this->request('list/*', []);
	}

	/**
	 * @param string $method
	 * @param array $data
	 *
	 * @return array
	 *
	 * @throws Exception
	 */
	protected function request(string $method, array $data = []): array
	{
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_HEADER, false);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_HTTPHEADER, ['Content-type: application/json']);
		curl_setopt($curl, CURLOPT_POST, true);
		curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));
		curl_setopt($curl, CURLOPT_URL, self::BASE_URL . $method);
		curl_setopt($curl, CURLOPT_TIMEOUT, 60);

		$response = curl_exec($curl);

		if ($response !== false) {
			$result = json_decode($response, true);
		} else {
			throw new Exception("Answer returned empty");
		}

		$status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
		if ($status !== 200) {
			throw new Exception(curl_error($curl), $status);
		}

		curl_close($curl);

		return $result;
	}
}
