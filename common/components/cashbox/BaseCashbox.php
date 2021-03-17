<?php
namespace common\components\cashbox;


use yii\base\Component;
use yii\base\Exception;
use yii\base\InvalidValueException;

/**
 * Class BaseCashbox
 * @package common\components\cashbox
 */
class BaseCashbox extends Component
{
	/**
	 * @var string
	 */
	public $url = 'https://fce.chekonline.ru:4443/fr/api/v2/Complex';

	/**
	 * @var string
	 */
	public $certificatePath;

	/**
	 * @var string
	 */
	public $privateKeyPath;

	/**
	 * @var array
	 */
	protected $data;

	/**
	 * @var int
	 */
	private static $countFactor = 1000;

	/**
	 * @var int
	 */
	private static $priceFactor = 100;

	/**
	 * @var resource
	 */
	private $curl;

	/**
	 * @var object|null
	 */
	private $response;

	/**
	 * Initializes the object
	 * @return void
	 */
	public function init()
	{
		parent::init();
		$this->initCertificate();
		$this->initPrivateKey();
	}

	/**
	 * Certificate validation
	 * @return void
	 */
	protected function initCertificate()
	{
		if (empty($this->certificatePath) or !file_exists($this->certificatePath)) {
			throw new InvalidValueException("Not a valid certificate path");
		}
	}

	/**
	 * Private key validation
	 * @return void
	 */
	protected function initPrivateKey()
	{
		if (empty($this->privateKeyPath) or !file_exists($this->privateKeyPath)) {
			throw new InvalidValueException("Not a valid private key path");
		}
	}

	/**
	 * @return int
	 */
	protected static function getCountFactor()
	{
		return self::$countFactor;
	}

	/**
	 * @return int
	 */
	protected static function getPriceFactor()
	{
		return self::$priceFactor;
	}

	/**
	 * Count in thousandths
	 * @param int $count
	 * @return int
	 */
	protected static function formatCount(int $count)
	{
		return $count * self::$countFactor;
	}

	/**
	 * Price in kopecks
	 * @param float $price
	 * @return float
	 */
	protected static function formatPrice(float $price)
	{
		return ceil($price) * self::$priceFactor;
	}

	/**
	 * Request in cashbox
	 * @return void
	 * @throws Exception
	 */
	protected function doRequest()
	{
		$this->initRequest();

		$response = $this->executeRequest();

		if ($response !== false) {
			$this->response = json_decode($response);
		} else {
			throw new Exception("Answer returned empty");
		}

		$status = $this->getRequestStatus();
		if ($status !== 200) {
			echo $this->getRequestError();
			throw new Exception($this->getRequestError(), $status);
		}

		$this->closeRequest();
	}

	/**
	 * @return object|null
	 */
	protected function getResponse()
	{
		return $this->response;
	}

	/**
	 * Get request format data
	 * @return string
	 */
	private function getRequestData()
	{
		return json_encode($this->data);
	}

	/**
	 * Initialize curl
	 * @return void
	 */
	private function initRequest()
	{
		$this->curl = curl_init();
		$this->setRequestOptions();
	}

	/**
	 * Set curl options
	 * @return void
	 */
	private function setRequestOptions()
	{
		curl_setopt($this->curl, CURLOPT_HEADER, false);
		curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($this->curl, CURLOPT_HTTPHEADER, ["Content-type: application/json"]);
		curl_setopt($this->curl, CURLOPT_POST, true);
		curl_setopt($this->curl, CURLOPT_POSTFIELDS, $this->getRequestData());
		curl_setopt($this->curl, CURLOPT_URL, $this->url);
		curl_setopt($this->curl, CURLOPT_VERBOSE, true);
		// Сертификат
		//curl_setopt($this->curl, CURLOPT_SSLCERT, $this->certificatePath);
		// Закрытый ключ
		//curl_setopt($this->curl, CURLOPT_SSLKEY, $this->privateKeyPath);
	}

	/**
	 * Execute curl request
	 * @return mixed
	 */
	private function executeRequest()
	{
		return curl_exec($this->curl);
	}

	/**
	 * Get request status
	 * @return mixed
	 */
	private function getRequestStatus()
	{
		return curl_getinfo($this->curl, CURLINFO_HTTP_CODE);
	}

	/**
	 * Get request error
	 * @return string
	 */
	private function getRequestError()
	{
		return curl_error($this->curl);
	}

	/**
	 * Close request
	 * @return void
	 */
	private function closeRequest()
	{
		curl_close($this->curl);
	}
}
