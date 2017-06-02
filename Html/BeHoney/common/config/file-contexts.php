<?php
/* Config file for flexibuild/file/ContextManager config */

return function () {
    $maxSize = Yii::$app->params['files']['defaultMaxSize'] * 1024 * 1024;

    return Yii::createObject([
        'class' => 'flexibuild\file\ContextManager',
        'contexts' => [
            'image-product' => [
                'class' => 'common\base\file\ImageContext',
                'validatorParams' => [
                    'maxSize' => $maxSize,
                ],
                'formatters' => [
                    'preview' => ['image/thumb', 'width' => 125, 'height' => 90],
                    'small' => ['image/thumb', 'width' => 200, 'height' => 200],
                    'medium' => ['image/thumb', 'width' => 600, 'height' => 600],
                ],
                'fileClass' => 'common\files\ProductImageFile',
                'fileConfig' => [
                    'defaultUrls' => [
                        '@frontendWeb/images/no-photo-image.png',
                        'medium' => '@frontendWeb/images/no-image.png',
                        'small' => '@frontendWeb/images/no-image.png',
                        'preview' => '@frontendWeb/images/no-image.png',
                    ],
                    'on cannotGetUrl' => 'flexibuild\file\events\CannotGetUrlHandlers::formatFileOnFly',
                ],
            ],
            'image-product-volume' => [
                'class' => 'common\base\file\ImageContext',
                'validatorParams' => [
                    'maxSize' => $maxSize,
                ],
                'formatters' => [
                    'underMain' => ['image/thumb', 'width' => 76, 'height' => 60],
                    'drop' => ['image/thumb', 'width' => 98, 'height' => 98],
                    'shopcart' => ['image/thumb', 'width' => 160, 'height' => 160],
                    'home' => ['image/thumb', 'width' => 300, 'height' => 240],
                    'main' => ['image/thumb', 'width' => 440, 'height' => 360],
                ],
                'fileClass' => 'common\files\ProductVolumeImageFile',
                'fileConfig' => [
                    'defaultUrls' => [
                        '@frontendWeb/images/no-image.png',
                        'underMain' => '@frontendWeb/images/no-image.png',
                        'drop' => '@frontendWeb/images/no-image.png',
                        'shopcart' => '@frontendWeb/images/no-image.png',
                        'home' => '@frontendWeb/images/no-image.png',
                        'main' => '@frontendWeb/images/no-image.png',
                    ],
                    'on cannotGetUrl' => 'flexibuild\file\events\CannotGetUrlHandlers::formatFileOnFly',
                ],
            ],
            'image-redactor' => [
                'class' => 'common\base\file\ImageContext',
                'validatorParams' => [
                    'maxSize' => $maxSize,
                ],
            ],
            'file-redactor' => [
                'class' => 'common\base\file\DefaultContext',
                'validatorParams' => [
                    'maxSize' => $maxSize * 10,
                ],
            ],
            'image-package' => [
                'class' => 'common\base\file\ImageContext',
                'validatorParams' => [
                    'maxSize' => $maxSize,
                ],
                'formatters' => [
                    'underMain' => ['image/thumb', 'width' => 76, 'height' => 60],
                    'drop' => ['image/thumb', 'width' => 98, 'height' => 98],
                    'home' => ['image/thumb', 'width' => 300, 'height' => 240],
                    'main' => ['image/thumb', 'width' => 440, 'height' => 366],
                ],
                'fileClass' => 'common\files\PackageImageFile',
                'fileConfig' => [
                    'defaultUrls' => [
                        '@frontendWeb/images/no-photo-image.png',
                        'underMain' => '@frontendWeb/images/no-image.png',
                        'drop' => '@frontendWeb/images/no-image.png',
                        'home' => '@frontendWeb/images/no-image.png',
                        'main' => '@frontendWeb/images/no-image.png',
                    ],
                    'on cannotGetUrl' => 'flexibuild\file\events\CannotGetUrlHandlers::formatFileOnFly',
                ],
            ],
            'photo-package' => [
                'class' => 'common\base\file\ImageContext',
                'validatorParams' => [
                    'maxSize' => $maxSize,
                ],
                'formatters' => [
                    'underMain' => ['image/thumb', 'width' => 76, 'height' => 60],
                    'drop' => ['image/thumb', 'width' => 98, 'height' => 98],
                    'shopcart' => ['image/thumb', 'width' => 160, 'height' => 160],
                    'home' => ['image/thumb', 'width' => 300, 'height' => 241],
                    'main' => ['image/thumb', 'width' => 440, 'height' => 360],
                ],
                'fileClass' => 'common\files\PackagePhotoFile',
                'fileConfig' => [
                    'defaultUrls' => [
                        '@frontendWeb/images/no-photo-image.png',
                        'underMain' => '@frontendWeb/images/no-image.png',
                        'drop' => '@frontendWeb/images/no-image.png',
                        'shopcart' => '@frontendWeb/images/no-image.png',
                        'home' => '@frontendWeb/images/no-image.png',
                        'main' => '@frontendWeb/images/no-image.png',
                    ],
                    'on cannotGetUrl' => 'flexibuild\file\events\CannotGetUrlHandlers::formatFileOnFly',
                ],
            ],
            'image-slide' => [
                'class' => 'common\base\file\ImageContext',
                'validatorParams' => [
                    'maxSize' => $maxSize,
                ],
                'formatters' => [
                    'preview' => ['image/thumb', 'width' => 70, 'height' => 70],
                    'small' => ['image/thumb', 'width' => 200, 'height' => 200],
                    'medium' => ['image/thumb', 'width' => 600, 'height' => 600],
                    'home' => ['image/thumb', 'width' => 1349, 'height' => 370],
                ],
                'fileClass' => 'common\files\SlideImageFile',
                'fileConfig' => [
                    'defaultUrls' => [
                        '@frontendWeb/images/no-photo-image.png',
                        'medium' => '@frontendWeb/images/no-image.png',
                        'small' => '@frontendWeb/images/no-image.png',
                        'preview' => '@frontendWeb/images/no-image.png',
                        'home' => '@frontendWeb/images/no-image.png',
                    ],
                    'on cannotGetUrl' => 'flexibuild\file\events\CannotGetUrlHandlers::formatFileOnFly',
                ],
            ],
            'image-review' => [
                'class' => 'common\base\file\ImageContext',
                'validatorParams' => [
                    'maxSize' => $maxSize,
                ],
                'formatters' => [
                    'preview' => ['image/thumb', 'width' => 96, 'height' => 96],
                    'small' => ['image/thumb', 'width' => 200, 'height' => 200],
                ],
                'fileClass' => 'common\files\ReviewImageFile',
                'fileConfig' => [
                    'defaultUrls' => [
                        '@frontendWeb/images/no-photo-image.png',
                        'preview' => '@frontendWeb/images/no-image.png',
                        'small' => '@frontendWeb/images/no-image.png',
                    ],
                    'on cannotGetUrl' => 'flexibuild\file\events\CannotGetUrlHandlers::formatFileOnFly',
                ],
            ],
            'image-magazine' => [
                'class' => 'common\base\file\ImageContext',
                'validatorParams' => [
                    'maxSize' => $maxSize,
                ],
                'formatters' => [
                    'preview' => ['image/thumb', 'width' => 96, 'height' => 96],
                    'medium' => ['image/thumb', 'width' => 240, 'height' => 240],
                    'small' => ['image/thumb', 'width' => 200, 'height' => 200],
                ],
                'fileClass' => 'common\files\MagazineImageFile',
                'fileConfig' => [
                    'defaultUrls' => [
                        '@frontendWeb/images/no-photo-image.png',
                        'preview' => '@frontendWeb/images/no-image.png',
                        'small' => '@frontendWeb/images/no-image.png',
                    ],
                    'on cannotGetUrl' => 'flexibuild\file\events\CannotGetUrlHandlers::formatFileOnFly',
                ],
            ],
            'image-news' => [
                'class' => 'common\base\file\ImageContext',
                'validatorParams' => [
                    'maxSize' => $maxSize,
                ],
                'formatters' => [
                    'preview' => ['image/thumb', 'width' => 96, 'height' => 96],
                    'medium' => ['image/thumb', 'width' => 240, 'height' => 240],
                    'small' => ['image/thumb', 'width' => 200, 'height' => 200],
                ],
                'fileClass' => 'common\files\NewsImageFile',
                'fileConfig' => [
                    'defaultUrls' => [
                        '@frontendWeb/images/no-photo-image.png',
                        'preview' => '@frontendWeb/images/no-image.png',
                        'small' => '@frontendWeb/images/no-image.png',
                    ],
                    'on cannotGetUrl' => 'flexibuild\file\events\CannotGetUrlHandlers::formatFileOnFly',
                ],
            ],
        ],
    ]);
};
