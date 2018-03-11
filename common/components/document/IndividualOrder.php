<?php

namespace common\components\document;

use common\components\document\classes\DocumentHelper;
use common\components\document\classes\GenerateXls;
use common\models\CatalogProduct;
use common\models\Order;
use common\models\OrderProduct;
use common\models\User;
use Yii;
use yii\base\Exception;
use yii\base\InvalidConfigException;
use yii\base\InvalidParamException;
use yii\web\NotFoundHttpException;

class IndividualOrder
{
    const TEMPLATE_FILE = '@common/components/document/templates/individual_order.xls';
    const RESULT_FILE_PATH = '@frontend/web/uploads/user/document/';

    const RESULT_FILE_PREFIX = 'fl_invoice';

    /**
     * @var string
     */
    public $templateFile = self::TEMPLATE_FILE;

    /**
     * @var string
     */
    public $resultFilePath = self::RESULT_FILE_PATH;

    /**
     * @var User
     */
    protected $user;

    /**
     * @var Order
     */
    protected $order;

    /**
     * @var OrderProduct[]
     */
    protected $products;

    /**
     * IndividualOrder constructor.
     * @param int $userId
     * @param int $orderId
     * @throws InvalidConfigException
     * @throws NotFoundHttpException
     */
    public function __construct(int $orderId, int $userId)
    {
        $this->user = User::findIdentity($userId);
        if ($this->user === null) {
            throw new  NotFoundHttpException('User not found.');
        }

        $this->order = Order::findOne($orderId);
        if ($this->order === null) {
            throw new NotFoundHttpException('Order not found.');
        }

        $this->products = self::getProducts($orderId);
        if (empty($this->products)) {
            throw new  NotFoundHttpException('Products not found.');
        }
    }

    /**
     * @return bool
     */
    public function validate()
    {
        $user = $this->user;
        return $user->name and $user->passport_series and
            $user->passport_number and $user->address and
            $user->phone;
    }

    /**
     * Get created file
     * @return string
     */
    public function getFile()
    {
        $resultFile = $this->getResultFile();
        if (file_exists($resultFile)) {
            return $resultFile;
        }

        return $this->generateFile();
    }

    /**
     * @return bool|string
     */
    public function getTemplateFile()
    {
        $file = Yii::getAlias($this->templateFile);
        if (file_exists($file)) {
            return $file;
        }

        throw new InvalidParamException("Template file not found.");
    }

    /**
     * Get result file
     * @return string
     */
    public function getResultFile()
    {
        return Yii::getAlias($this->resultFilePath) . $this->getResultFilename();
    }

    /**
     * Attachment name
     * @return string
     */
    public function getAttachmentName()
    {
        $order = $this->order;

        return
            "Счет по заказу №" .
            $order->id . " от " . date('d.m.Y', strtotime($order->created_at)) .
            '.xls';
    }

    /**
     * @param $orderId
     * @return array|OrderProduct[]
     */
    protected static function getProducts($orderId)
    {
        return OrderProduct::find()
            ->select([
                'title' => CatalogProduct::tableName() . '.title',
                'quantity' => OrderProduct::tableName() . '.quantity',
                'price' => OrderProduct::tableName() . '.price'
            ])
            ->where(['order_id' => $orderId])
            ->joinWith(['product'], false)
            ->asArray()
            ->all();
    }

    /**
     * The result file name
     * @return string
     */
    protected function getResultFilename()
    {
        return self::RESULT_FILE_PREFIX . $this->user->getId() . '_' . $this->order->id . '.xls';
    }

    /**
     * Generate file
     * @return string Return result file path or false
     * @throws Exception
     */
    protected function generateFile()
    {
        $templateFile = $this->getTemplateFile();
        $resultFile = $this->getResultFile();
        $data = $this->getData();

        $generate = new GenerateXls($templateFile, $resultFile, $data);

        $resultFile = $generate->execute();
        if ($resultFile === false) {
            throw new Exception("File not created.");
        }

        return $resultFile;
    }

    protected function getData()
    {
        $result = array_merge(
            $this->getOrderData(),
            $this->getUserData(),
            $this->getProductRowsData(),
            $this->getTotalData()
        );

        return $result;
    }

    /**
     * Get product rows
     * @return array
     */
    protected function getProductRowsData()
    {
        $result = [];
        foreach ($this->products as $index => $product) {
            $result['order'][] = [
                'num' => $index + 1,
                'name' => $product['title'],
                'count' => $product['quantity'],
                'unit' => 1,
                'price' => DocumentHelper::priceFormat($product['price']),
                'sum' => DocumentHelper::priceFormat($product['price']),
            ];
        }

        return $result;
    }

    /**
     * Get order data
     * @return array
     */
    protected function getOrderData()
    {
        $order = $this->order;

        return [
            'order_id' => $order->id,
            'order_date' => date('d.m.Y', strtotime($order->created_at)),
        ];
    }

    /**
     * Get user data
     * @return array
     */
    protected function getUserData()
    {
        $user = $this->user;

        return [
            'user_name' => $user->name,
            'user_passport' => $user->passport_series . ' ' . $user->passport_number,
            'user_address' => $user->address,
            'user_phone' => "8{$user->phone}",
        ];
    }

    /**
     * Get total data
     * @return array
     */
    protected function getTotalData()
    {
        $sum = $this->getTotalSum();

        return [
            'total_sum' => DocumentHelper::priceFormat($sum),
            'total_count' => count($this->products),
            'total_sum_str' => DocumentHelper::number2string($sum),
        ];
    }

    /**
     * @return int|mixed
     */
    protected function getTotalSum()
    {
        $sum = 0;
        foreach ($this->products as $product) {
            $sum += $product['price'] * $product['quantity'];
        }

        return $sum;
    }
}