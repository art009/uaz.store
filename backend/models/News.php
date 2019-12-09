<?php

namespace backend\models;

use Yii;
use yii\base\InvalidConfigException;
use yii\web\UploadedFile;
use common\components\ImageHandler;
use common\components\AppHelper;

/**
 * Class News
 *
 * @property UploadedFile $file
 * @property string $sourceFile
 *
 * @package backend\models
 */
class News extends \common\models\News
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
     * @inheritdoc
     */
    public function rules()
    {
        return parent::rules() + [
            [['file'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg'],
		    [['sourceFile'], 'safe'],
		];
    }

    /**
     * Deletes model image
     */
    protected function deleteImages()
    {
        if ($this->image) {
            $uploadsFolder = AppHelper::uploadsFolder();
            @unlink($uploadsFolder . '/' . self::FOLDER . '/' . $this->image);
            @unlink($uploadsFolder . '/' . self::FOLDER_SMALL . '/' . $this->image);
            @unlink($uploadsFolder . '/' . self::FOLDER_MEDIUM . '/' . $this->image);
        }
    }

    /**
     * @param bool $insert
     *
     * @return bool
     *
     * @throws InvalidConfigException
     * @throws \Exception
     */
    public function beforeSave($insert)
    {
        $result = false;
        if (parent::beforeSave($insert)) {
            if ($this->file) {
                $name = md5($this->id . time()) . '.' . $this->file->extension;
                $uploadsFolder = AppHelper::uploadsFolder();
                if ($this->file->saveAs($uploadsFolder . '/' . self::FOLDER . '/' . $name)) {
                	$this->saveImage($uploadsFolder . '/' . self::FOLDER . '/' . $name, $name);
                } else {
                    throw new InvalidConfigException('Директория недоступна для записи: ' . $uploadsFolder . '/' . self::FOLDER . '/');
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
     * @throws \Exception
     */
    protected function saveImage($sourceFile, $name)
    {
    	if (file_exists($sourceFile)) {
    	    if (!empty($this->image)) {
    	        $this->deleteImages();
            }
		    $this->image = $name;
		    $uploadsFolder = AppHelper::uploadsFolder();
		    $imageHandler = new ImageHandler();
		    $imageHandler
			    ->load($sourceFile)
			    ->resize(false, self::MAX_HEIGHT)
			    ->watermark(AppHelper::watermarkFile(), 0, 0, ImageHandler::CORNER_CENTER)
			    ->save($uploadsFolder . '/' . self::FOLDER . '/' . $name, false, 100)
			    ->reload()
			    ->resize(false, self::MAX_HEIGHT)
			    ->watermark(AppHelper::watermarkFile(), 0, 0, ImageHandler::CORNER_CENTER)
			    ->resizeCanvas(self::SMALL_IMAGE_WIDTH, self::SMALL_IMAGE_HEIGHT)
			    ->save($uploadsFolder . '/' . self::FOLDER_SMALL . '/' . $name, false, 100)
			    ->reload()
			    ->resize(false, self::MAX_HEIGHT)
			    ->watermark(AppHelper::watermarkFile(), 0, 0, ImageHandler::CORNER_CENTER)
			    ->resizeCanvas(self::MEDIUM_IMAGE_WIDTH, self::MEDIUM_IMAGE_HEIGHT)
			    ->save($uploadsFolder . '/' . self::FOLDER_MEDIUM . '/' . $name, false, 100);
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

    /**
     * @throws \Exception
     */
    public function reSaveImage()
    {
	    $name = $this->image;
	    $file = AppHelper::uploadsFolder() . '/' . self::FOLDER . '/' . $name;
	    $this->saveImage($file, $name);
    }
}
