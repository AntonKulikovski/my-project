<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "product_tag".
 *
 */
class PackageTag extends \common\models\PackageTag
{
    /**
     * @param string $slug
     * @return array
     */
    public static function getPackageTagByName($slug = 'all')
    {
        $query = PackageTag::find()
            ->select('packageId')
            ->joinWith('package')
            ->andWhere([Package::tableName() . '.[[active]]' => true])
            ->distinct();

        if ($slug != 'all') {
            $query->joinWith('tag')
                ->andWhere([Tag::tableName() . '.[[slug]]' => $slug]);
        }

        return $query->all();
    }
}
