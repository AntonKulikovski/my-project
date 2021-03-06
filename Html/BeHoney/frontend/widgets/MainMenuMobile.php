<?php

namespace frontend\widgets;

use frontend\models\Category;
use frontend\models\Page;
use Yii;
use yii\helpers\Url;
use yii\widgets\Menu;

class MainMenuMobile extends Menu
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
        $items =[];
        $categories = Category::find()
            ->orderBy('position')
            ->all();

        foreach ($categories as $category) {
            $items[] = [
                'label' => $category->name,
                'url' => Url::to(['category/index', 'slug' => $category->slug]),
                'template' => "<a href=\"{url}\"  class=\"nav__item\">{label}</a>",
            ];
        }

        $items[] = [
            'label' => Page::getModelByNameFixed('package')->name,
            'url' => Url::to(['package/index', 'slug' => 'all']),
            'template' => "<a href=\"{url}\"  class=\"nav__item\">{label}</a>",
        ];
        $menuItems = [
            [
                'template' => '',
                'submenuTemplate' => "{items}",
                'options' => [
                    'class' => 'mob-nav__list',
                    'tag' => 'ul',
                ],
                'items' => [
                    [
                        'label' => 'Каталог',
                        'template' => "<span class=\"nav__item\">{label} <i class=\"fa fa-chevron-down\" aria-hidden=\"true\"></i></span>",
                        'options' => [
                            'class' => 'drop',
                        ],
                        'submenuTemplate' => "<div class=\"drop-list__wrap\"><ul class=\"drop-list\">{items}</ul></div>",
                        'items' => $items,
                    ],
                    [
                        'label' => Page::getModelByNameFixed('pay')->name,
                        'url' => Url::to(["site/pay"]),
                        'template' => "<a href=\"{url}\"  class=\"nav__item\">{label}</a>",
                    ],
                    [
                        'label' => Page::getModelByNameFixed('contact')->name,
                        'url' => Url::to(["site/contact"]),
                        'template' => "<a href=\"{url}\"  class=\"nav__item\">{label}</a>",
                    ],
                    [
                        'label' => Page::getModelByNameFixed('about')->name,
                        'url' => Url::to(["site/about"]),
                        'template' => "<a href=\"{url}\"  class=\"nav__item\">{label}</a>",
                    ],
                    [
                        'label' => Page::getModelByNameFixed('magazine')->name,
                        'url' => Url::to(["site/magazine"]),
                        'template' => "<a href=\"{url}\"  class=\"nav__item\">{label}</a>",
                    ],
                    [
                        'label' => Page::getModelByNameFixed('news')->name,
                        'url' => Url::to(["news/index"]),
                        'template' => "<a href=\"{url}\"  class=\"nav__item\">{label}</a>",
                    ],
                ],
            ],
            [
                'template' => '',
                'submenuTemplate' => "{items}",
                'options' => [
                    'class' => 'shared shared--tablet',
                    'tag' => 'div',
                ],
                'items' => [
                    [
                        'options' => [
                            'class' => 'shared__icon shared__icon--inst',
                            'tag' => 'a',
                            'href' => "#",
                        ],
                        'template' => "<i class=\"fa fa-instagram\" aria-hidden=\"true\"></i>",
                    ],
                    [
                        'options' => [
                            'class' => 'shared__icon shared__icon--fb',
                            'tag' => 'a',
                            'href' => "#",
                        ],
                        'template' => "<i class=\"fa fa-facebook-square\" aria-hidden=\"true\"></i>",
                    ],
                    [
                        'options' => [
                            'class' => 'shared__icon shared__icon--vk',
                            'tag' => 'a',
                            'href' => "#",
                        ],
                        'template' => "<i class=\"fa fa-vk\" aria-hidden=\"true\"></i>",
                    ],
                ],
            ],
        ];

        return $menuItems;
    }
}
