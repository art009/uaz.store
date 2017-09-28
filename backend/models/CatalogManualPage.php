<?php

namespace backend\models;

use Yii;
use yii\web\UploadedFile;
use yii\helpers\ArrayHelper;
use common\components\AppHelper;
use common\components\ImageHandler;

/**
 * Class ManualPage
 *
 * @property UploadedFile $imageFile
 *
 * @package backend\models
 */
class CatalogManualPage extends \common\models\CatalogManualPage
{
	/**
	 * @var UploadedFile
	 */
	public $imageFile;

	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return parent::rules() + [
				[['imageFile'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg'],
			];
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		return ArrayHelper::merge(parent::attributeLabels(), [
			'imageFile' => 'Загружаемая картинка',
		]);
	}

	/**
	 * @inheritdoc
	 */
	public function load($data, $formName = null)
	{
		$result = parent::load($data, $formName = null);
		$this->imageFile = UploadedFile::getInstance($this, 'imageFile');

		return $result;
	}

	/**
	 * Deletes model image
	 */
	protected function deleteImages()
	{
		if ($this->image) {
			$uploadsFolder = AppHelper::uploadsFolder();
			@unlink($uploadsFolder . '/' . self::FOLDER . '/' . $this->image);
			@unlink($uploadsFolder . '/' . self::FOLDER_MEDIUM . '/' . $this->image);
		}
	}

	/**
	 * @param bool $insert
	 *
	 * @return bool
	 */
	public function beforeSave($insert)
	{
		$result = true;
		if (parent::beforeSave($insert)) {
			if ($this->imageFile) {
				$name = md5(time()) . '.' . $this->imageFile->extension;
				$uploadsFolder = AppHelper::uploadsFolder();
				if ($this->imageFile->saveAs($uploadsFolder . '/' . self::FOLDER . '/' . $name)) {
					$this->deleteImages();
					$this->image = $name;

					/* @var $imageHandler ImageHandler */
					$imageHandler = Yii::$app->ih;
					$imageHandler
						->load($uploadsFolder . '/' . self::FOLDER . '/' . $name)
						->watermark(AppHelper::watermarkFile(), 0, 0, ImageHandler::CORNER_CENTER)
						->save($uploadsFolder . '/' . self::FOLDER . '/' . $name, false, 100)
						->reload()
						->resizeCanvas(self::MEDIUM_IMAGE_WIDTH, self::MEDIUM_IMAGE_HEIGHT)
						->save($uploadsFolder . '/' . self::FOLDER_MEDIUM . '/' . $name, false, 100);
				} else {
					$this->addError('imageFile', 'Директория недоступна для записи: ' . $uploadsFolder . '/' . self::FOLDER . '/');
					$result = false;
				}
			}
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
