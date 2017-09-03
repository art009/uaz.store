<?php

namespace backend\models;

use Yii;
use yii\base\InvalidConfigException;
use yii\web\UploadedFile;
use common\components\ImageHandler;
use common\components\AppHelper;

/**
 * Class CatalogProductImage
 *
 * @property UploadedFile $file
 * @property integer $num
 * @property string $sourceFile
 *
 *
 * @property \backend\models\CatalogProduct $product
 *
 * @package backend\models
 */
class CatalogProductImage extends \common\models\CatalogProductImage
{
    /**
     * @var UploadedFile
     */
    public $file;

	/**
	 * @var string
	 */
    public $sourceFile;

    /**
     * @var integer
     */
    public $num;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return parent::rules() + [
            [['file'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg'],
		    [['sourceFile', 'num'], 'safe'],
		];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProduct()
    {
        return $this->hasOne(CatalogProduct::className(), ['id' => 'product_id']);
    }

    /**
     * Deletes model image
     */
    protected function deleteImages()
    {
        if ($this->image) {
            $uploadsFolder = AppHelper::uploadsFolder();
            @unlink($uploadsFolder . '/' . CatalogProduct::FOLDER . '/' . $this->image);
            @unlink($uploadsFolder . '/' . CatalogProduct::FOLDER_SMALL . '/' . $this->image);
            @unlink($uploadsFolder . '/' . CatalogProduct::FOLDER_MEDIUM . '/' . $this->image);
        }
    }

    /**
     * @param bool $insert
     *
     * @return bool
     *
     * @throws InvalidConfigException
     */
    public function beforeSave($insert)
    {
        $result = false;
        if (parent::beforeSave($insert)) {
            if ($this->file) {
                $name = md5($this->num . time()) . '.' . $this->file->extension;
                $uploadsFolder = AppHelper::uploadsFolder();
                if ($this->file->saveAs($uploadsFolder . '/' . CatalogProduct::FOLDER . '/' . $name)) {
                	$this->saveImage($uploadsFolder . '/' . CatalogProduct::FOLDER . '/' . $name, $name);
                } else {
                    throw new InvalidConfigException('Директория недоступна для записи: ' . $uploadsFolder . '/' . CatalogProduct::FOLDER . '/');
                }
            }
            if ($this->sourceFile) {
	            $name = md5($this->sourceFile . time()) . '.' . pathinfo($this->sourceFile, PATHINFO_EXTENSION);
	            $this->saveImage($this->sourceFile, $name);
            }

            $result = true;
        }
        return $result;
    }

	/**
	 * @param string $sourceFile
	 * @param string $name
	 */
    protected function saveImage($sourceFile, $name)
    {
    	if (file_exists($sourceFile)) {
		    $this->image = $name;
		    $uploadsFolder = AppHelper::uploadsFolder();
		    $imageHandler = new ImageHandler();
		    $imageHandler
			    ->load($sourceFile)
			    ->watermark(AppHelper::watermarkFile(), 0, 0, ImageHandler::CORNER_CENTER)
			    ->save($uploadsFolder . '/' . CatalogProduct::FOLDER . '/' . $name)
			    ->reload()
			    ->resizeCanvas(CatalogProduct::SMALL_IMAGE_WIDTH, CatalogProduct::SMALL_IMAGE_HEIGHT)
			    ->save($uploadsFolder . '/' . CatalogProduct::FOLDER_SMALL . '/' . $name)
			    ->reload()
			    ->resizeCanvas(CatalogProduct::MEDIUM_IMAGE_WIDTH, CatalogProduct::MEDIUM_IMAGE_HEIGHT)
			    ->save($uploadsFolder . '/' . CatalogProduct::FOLDER_MEDIUM . '/' . $name);
	    }
    }

    /**
     * @inheritdoc
     */
    public function beforeDelete()
    {
        if (parent::beforeDelete()) {

            $this->deleteImages();

            return true;
        } else {
            return false;
        }
    }
}
