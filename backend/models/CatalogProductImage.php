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
                    $this->image = $name;

                    /* @var $imageHandler ImageHandler */
                    $imageHandler = Yii::$app->ih;
                    $imageHandler
                        ->load($uploadsFolder . '/' . CatalogProduct::FOLDER . '/' . $name)
                        ->watermark(AppHelper::watermarkFile(), 0, 0, ImageHandler::CORNER_CENTER)
                        ->save($uploadsFolder . '/' . CatalogProduct::FOLDER . '/' . $name)
                        ->reload()
                        ->resizeCanvas(CatalogProduct::SMALL_IMAGE_WIDTH, CatalogProduct::SMALL_IMAGE_HEIGHT)
                        ->save($uploadsFolder . '/' . CatalogProduct::FOLDER_SMALL . '/' . $name)
                        ->reload()
                        ->resizeCanvas(CatalogProduct::MEDIUM_IMAGE_WIDTH, CatalogProduct::MEDIUM_IMAGE_HEIGHT)
                        ->save($uploadsFolder . '/' . CatalogProduct::FOLDER_MEDIUM . '/' . $name);
                } else {
                    throw new InvalidConfigException('Директория недоступна для записи: ' . $uploadsFolder . '/' . CatalogProduct::FOLDER . '/');
                }
            }

            $result = true;
        }
        return $result;
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
