<?php
/**
 * The manifest of files that are local to specific environment.
 * This file returns a list of environments that the application
 * may be installed under. The returned data must be in the following
 * format:
 *
 * ```php
 * return [
 *     'environment name' => [
 *         'path' => 'directory storing the local files',
 *         'skipFiles'  => [
 *             // list of files that should only copied once and skipped if they already exist
 *         ],
 *         'setWritable' => [
 *             // list of directories that should be set writable
 *         ],
 *         'setExecutable' => [
 *             // list of files that should be set executable
 *         ],
 *         'setCookieValidationKey' => [
 *             // list of config files that need to be inserted with automatically generated cookie validation keys
 *         ],
 *         'createSymlink' => [
 *             // list of symlinks to be created. Keys are symlinks, and values are the targets.
 *         ],
 *     ],
 * ];
 * ```
 */
return [
    'Development' => [
        'path' => 'dev',
        'setWritable' => [
            'backend/runtime',
            'backend/web/assets',
            'frontend/runtime',
            'frontend/web/assets',
            'common/models',
            'backend/controllers',
            'backend/models',
            'backend/views',
            'frontend/web/uploads',
            'frontend/web/uploads/catalog-category',
            'frontend/web/uploads/catalog-category/m',
            'frontend/web/uploads/catalog-category/s',
            'frontend/web/uploads/catalog-product',
            'frontend/web/uploads/catalog-product/m',
            'frontend/web/uploads/catalog-product/s',
            'frontend/web/min',
			'frontend/web/uploads/catalog-manual',
			'frontend/web/uploads/catalog-manual/m',
			'frontend/web/uploads/catalog-manual-page',
			'frontend/web/uploads/catalog-manual-page/m',
			'frontend/web/uploads/user',
			'frontend/web/uploads/user/s',
			'frontend/web/uploads/page',
	        'frontend/web/uploads/user/document',
		],
        'setExecutable' => [
            'yii',
            'yii_test',
        ],
        'setCookieValidationKey' => [
            'backend/config/main-local.php',
            'frontend/config/main-local.php',
        ],
    ],
    'Production' => [
        'path' => 'prod',
        'setWritable' => [
            'backend/runtime',
            'backend/web/assets',
            'frontend/runtime',
            'frontend/web/assets',
            'frontend/web/uploads',
            'frontend/web/uploads/catalog-category',
            'frontend/web/uploads/catalog-category/m',
            'frontend/web/uploads/catalog-category/s',
            'frontend/web/uploads/catalog-product',
            'frontend/web/uploads/catalog-product/m',
            'frontend/web/uploads/catalog-product/s',
			'frontend/web/min',
			'frontend/web/uploads/catalog-manual',
			'frontend/web/uploads/catalog-manual/m',
			'frontend/web/uploads/catalog-manual-page',
			'frontend/web/uploads/catalog-manual-page/m',
			'frontend/web/uploads/user',
			'frontend/web/uploads/user/s',
	        'frontend/web/uploads/page',
	        'frontend/web/uploads/user/document',
        ],
        'setExecutable' => [
            'yii',
        ],
        'setCookieValidationKey' => [
            'backend/config/main-local.php',
            'frontend/config/main-local.php',
        ],
    ],
];
