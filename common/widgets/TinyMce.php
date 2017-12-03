<?php

namespace common\widgets;

use common\components\AppHelper;

/**
 * Class TinyMce
 *
 * @package common\widgets
 */
class TinyMce extends \dosamigos\tinymce\TinyMce
{
    /**
     * @inheritdoc
     */
    public $language = 'ru';

    /**
     * @inheritdoc
     */
    public $options = [
        'rows' => 16,
        'class' => 'form-control',
    ];

    /**
     * @inheritdoc
     */
    public $clientOptions = [
        'plugins' => [
            "advlist autolink lists link image imagetools charmap print preview anchor",
            "searchreplace visualblocks code fullscreen",
            "insertdatetime media table contextmenu paste"
        ],
        'toolbar' => "undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image"
    ];

	/**
	 * @inheritdoc
	 */
    protected function registerClientScript()
    {
    	$this->clientOptions['images_upload_url'] = '/page/upload';
    	$this->clientOptions['images_upload_base_path'] = AppHelper::uploadsPath() . '/page';

    	parent::registerClientScript();
    }
}
