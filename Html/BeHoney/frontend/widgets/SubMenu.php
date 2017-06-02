<?php

namespace frontend\widgets;

use frontend\models\Category;
use frontend\models\Page;
use Yii;
use yii\helpers\Url;
use yii\widgets\Menu;

class SubMenu extends Menu
{
    public $activateParents = true;

    public function init()
    {
        if (!count($this->items)) {
            $this->items = static::menuItems();
        }

        parent::init();
    }


    /**
     * @return array of menu items configuration.
     */
    public static function menuItems()
    {
        /** @var Category $category */
        $items = [];
        $categories = Category::find()
            ->orderBy('position')
            ->all();

        foreach ($categories as $category) {
            $items[] = [
                'label' => $category->name,
                'url' => Url::to(['category/index', 'slug' => $category->slug]),
                'template' => "<a href=\"{url}\"  class=\"footer__link\">{label}</a>",

            ];
        }

        $items[] = [
            'label' => Page::getModelByNameFixed('package')->name,
            'url' => Url::to(['package/index', 'slug' => 'all']),
            'template' => "<a href=\"{url}\"  class=\"footer__link\">{label}</a>",
        ];
        $menuItems = [
            [
                'template' => '',
                'submenuTemplate' => "{items}",
                'options' => [
                    'class' => 'footer__column hide-phone',
                    'tag' => 'div',
                ],
                'items' => [
                    [
                        'template' => '',
                        'submenuTemplate' => "{items}",
                        'options' => [
                            'class' => 'footer__list',
                            'tag' => 'ul',
                        ],
                        'items' => $items,
                    ],
                ],
            ],
            [
                'template' => '',
                'submenuTemplate' => "{items}",
                'options' => [
                    'class' => 'footer__column',
                    'tag' => 'div',
                ],
                'items' => [
                    [
                        'template' => '',
                        'submenuTemplate' => "{items}",
                        'options' => [
                            'class' => 'footer__list',
                            'tag' => 'ul',
                        ],
                        'items' => [
                            [
                                'label' => Page::getModelByNameFixed('about')->name,
                                'url' => Url::to(['site/about/']),
                                'template' => "<a href=\"{url}\"  class=\"footer__link\">{label}</a>",
                            ],
                            [
                                'label' => Page::getModelByNameFixed('pay')->name,
                                'url' => Url::to(['site/pay/']),
                                'template' => "<a href=\"{url}\"  class=\"footer__link\">{label}</a>",
                            ],
                            [
                                'label' => 'Публичная оферта',
                                'url' => Url::to(['site/offer/']),
                                'template' => "<a href=\"{url}\"  class=\"footer__link\">{label}</a>",
                            ],
                        ],
                    ],
                ],
            ],
            [
                'template' => '',
                'submenuTemplate' => "{items}",
                'options' => [
                    'class' => 'footer__column',
                    'tag' => 'div',
                ],
                'items' => [
                    [
                        'template' => '',
                        'submenuTemplate' => "{items}",
                        'options' => [
                            'class' => 'footer__list',
                            'tag' => 'ul',
                        ],
                        'items' => [
                            [
                                'label' => Page::getModelByNameFixed('order')->name,
                                'url' => Url::to(['site/order/']),
                                'template' => "<a href=\"{url}\"  class=\"footer__link\">{label}</a>",
                            ],
                            [
                                'label' => Page::getModelByNameFixed('politic')->name,
                                'url' => Url::to(['site/politic/']),
                                'template' => "<a href=\"{url}\"  class=\"footer__link\">{label}</a>",
                            ],
                            [
                                'label' => Page::getModelByNameFixed('review')->name,
                                'url' => Url::to(['review/index/']),
                                'template' => "<a href=\"{url}\"  class=\"footer__link\">{label}</a>",
                            ],
                        ],
                    ],
                ],
            ],
            [
                'template' => '',
                'submenuTemplate' => "{items}",
                'options' => [
                    'class' => 'footer__column hide-phone',
                    'tag' => 'div',
                ],
                'items' => [
                    [
                        'template' => '',
                        'submenuTemplate' => "{items}",
                        'options' => [
                            'class' => 'footer__list',
                            'tag' => 'ul',
                        ],
                        'items' => [
                            [
                                'template' => "<span itemprop='workTime' class=\"footer__info\">10:00-18:00, ПН-ПТ,<br> 11:00-16:00, СБ</span>",
                                'options' => [
                                    'itemscope' => '',
                                    'itemtype' => "http://schema.org/Worktime"
                                ]
                            ],
                            [
                                'template' => "<a itemprop='url' href='tel:+375296580898' class=\"footer__info\"><span itemprop='phoneNumber'>+375 (29) 658-08-98</span></a>",
                                'options' => [
                                    'itemscope' => '',
                                    'itemtype' => "http://schema.org/Phone"
                                ]
                            ],
                            [
                                'template' => "<a itemprop='mailAddress' href='mailto:info@behoney.by' class=\"footer__info\">info@behoney.by</a>",
                                'options' => [
                                    'itemscope' => '',
                                    'itemtype' => "http://schema.org/Mail"
                                ]
                            ],
                        ],

                    ],
                ],

            ],

        ];

        return $menuItems;
    }
}


