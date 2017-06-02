<?php
return [
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'bootstrap' => [
        'setAliases' => function () {
            $params = Yii::$app->params;
            if (!isset($params['frontendWeb']) || !$params['frontendWeb']) {
                throw new \yii\base\InvalidConfigException('You mush set the frontendWeb alias in your params-local file');
            }

            Yii::setAlias('@frontendWeb', $params['frontendWeb']);
        }
    ],
    'language' => 'ru-RU',
    'components' => [
        'cache' => [
            'class' => 'yii\caching\FileCache',
            'cachePath' => '@backend/runtime/cache'
        ],
        'i18n' => [
            'translations' => [
                'common*' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'basePath' => '@common/messages',
                ],
            ],
        ],
        'view' => [
            'renderers' => [
                'sphp' => [
                    'class' => 'flexibuild\phpsafe\ViewRenderer',
                    'compiledPath' => '@common/runtime/phpsafe/compiled',
                ],
            ],
        ],
        'contextManager' => require(__DIR__ . DIRECTORY_SEPARATOR . 'file-contexts.php'),
    ],
];
