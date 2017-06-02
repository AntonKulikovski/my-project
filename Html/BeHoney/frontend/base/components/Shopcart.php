<?php

namespace frontend\base\components;

use frontend\models\Package;
use frontend\models\ProductVolume;
use Yii;
use yii\base\Component;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;

/**
 * Class Shopcart
 * @package frontend\base\helpers
 *
 * Shopcart is used to add/remove/get/ product
 * structure: shopcart => [
 *      shopcart => [],
 *      total-count => number,
 *      total-sum => number,
 *
 * ]
 *
 */
class Shopcart extends Component
{
    const SHOPCART_PARAM = 'shopcart';
    const COUNT_PARAM = 'total-count';
    const SUM_PARAM = 'total-sum';
    const PRODUCT_VOLUMES_PARAM = 'product-volumes';
    const PACKAGE_PARAM_STANDARD = 'packageStandard';
    const PACKAGE_PARAM_ONE = 'packageOne';
    const PACKAGE_PARAM_ASORTI = 'packageAsorti';

    /**
     * @var array
     */
    private $products;

    /**
     * @return Shopcart
     */
    protected function recalculateTotalValues()
    {
        $shopcartData = $this->getSessionShopcartData();
        $count = 0;
        $sum = 0;
        $products = $this->getShopcartProduct();

        if (isset($products[self::PRODUCT_VOLUMES_PARAM])) {
            foreach ($products[self::PRODUCT_VOLUMES_PARAM] as $product) {
                $count += $this->getShopcartProductCount($product);
                $sum += $this->getShopcartProductSum($product);
            }
        }
        if (isset($products[self::PACKAGE_PARAM_STANDARD])) {
            foreach ($products[self::PACKAGE_PARAM_STANDARD] as $product) {
                $count += $this->getShopcartProductCount($product[0], Package::TYPE_STANDARD, $product[1], $product[2]);
                $sum += $this->getShopcartProductSum($product[0], Package::TYPE_STANDARD, $product[1], $product[2]);
            }
        }
        if (isset($products[self::PACKAGE_PARAM_ONE])) {
            foreach ($products[self::PACKAGE_PARAM_ONE] as $product) {
                $count += $this->getShopcartProductCount($product[0], Package::TYPE_ONE, $product[1]);
                $sum += $this->getShopcartProductSum($product[0], Package::TYPE_ONE, $product[1]);
            }
        }
        if (isset($products[self::PACKAGE_PARAM_ASORTI])) {
            foreach ($products[self::PACKAGE_PARAM_ASORTI] as $product) {
                $count += $this->getShopcartProductCount($product, Package::TYPE_ASORTI);
                $sum += $this->getShopcartProductSum($product, Package::TYPE_ASORTI);
            }
        }


        $shopcartData[self::COUNT_PARAM] = $count;
        $shopcartData[self::SUM_PARAM] = $sum;
        $this->setSessionShopcartData($shopcartData);

        return $this;
    }

    /**
     * @param ProductVolume|Package $product
     * @param integer $count
     * @param ProductVolume|null $productFirst
     * @param ProductVolume|null $productSecond
     * @param string|null $typePackage
     * @return $this Shopcart
     */
    public function addProduct($product, $count = 1, $typePackage = null, $productFirst = null, $productSecond = null)
    {
        $this->changeProduct(
            $product,
            $this->getShopcartProductCount($product, $typePackage, $productFirst, $productSecond) + $count,
            $typePackage,
            $productFirst,
            $productSecond
        );

        return $this;
    }

    /**
     * @param ProductVolume|Package $product
     * @param integer $count
     * @param ProductVolume|null $productFirst
     * @param ProductVolume|null $productSecond
     * @param string|null $typePackage
     * @return $this Shopcart
     */
    public function changeProduct($product, $count = 1, $typePackage = null, $productFirst = null, $productSecond = null)
    {
        $shopcartData = $this->getSessionShopcartData();

        if ($product instanceof ProductVolume) {
            $shopcartData[self::PRODUCT_VOLUMES_PARAM][$product->id] = $count;

            if (isset($this->product)) {
                $this->products[self::PRODUCT_VOLUMES_PARAM][$product->id] = $product;
            }
        }
        if ($product instanceof Package && $typePackage == Package::TYPE_STANDARD) {
            $shopcartData[self::PACKAGE_PARAM_STANDARD][$product->id . '-' . $productFirst->id . '-' . $productSecond->id] = $count;

            if (isset($this->product)) {
                $this->products[self::PACKAGE_PARAM_STANDARD][$product->id . '-' . $productFirst->id . '-' . $productSecond->id][] = $product;
                $this->products[self::PACKAGE_PARAM_STANDARD][$product->id . '-' . $productFirst->id . '-' . $productSecond->id][] = $productFirst;
                $this->products[self::PACKAGE_PARAM_STANDARD][$product->id . '-' . $productFirst->id . '-' . $productSecond->id][] = $productSecond;
            }
        }
        if ($product instanceof Package && $typePackage == Package::TYPE_ONE) {
            $shopcartData[self::PACKAGE_PARAM_ONE][$product->id . '-' . $productFirst->id] = $count;

            if (isset($this->product)) {
                $this->products[self::PACKAGE_PARAM_ONE][$product->id . '-' . $productFirst->id][] = $product;
                $this->products[self::PACKAGE_PARAM_ONE][$product->id . '-' . $productFirst->id][] = $productFirst;
            }
        }
        if ($product instanceof Package && $typePackage == Package::TYPE_ASORTI) {
            $shopcartData[self::PACKAGE_PARAM_ASORTI][$product->id] = $count;

            if (isset($this->product)) {
                $this->products[self::PACKAGE_PARAM_ASORTI][$product->id] = $product;
            }
        }

        $this->setSessionShopcartData($shopcartData);

        $this->recalculateTotalValues();

        return $this;
    }

    /**
     * Clear the user's session
     * @return $this Shopcart
     */
    public function clearShopcart()
    {
        $this->setSessionShopcartData([]);
        $this->products = null;
        $this->recalculateTotalValues();
        
        return $this;
    }

    /**
     * @param ProductVolume|Package $product
     * @param ProductVolume|null $productFirst
     * @param ProductVolume|null $productSecond
     * Delete the user's product from shopcart
     * @param string|null $typePackage
     * @return $this Shopcart
     */
    public function deleteProduct($product, $typePackage = null, $productFirst = null, $productSecond = null)
    {
        $shopcartData = $this->getSessionShopcartData();

        if ($product instanceof ProductVolume) {
            if (isset($shopcartData[self::PRODUCT_VOLUMES_PARAM][$product->id])) {
                unset($shopcartData[self::PRODUCT_VOLUMES_PARAM][$product->id]);
            }
            if (isset($this->products[self::PRODUCT_VOLUMES_PARAM][$product->id])) {
                unset($this->products[self::PRODUCT_VOLUMES_PARAM][$product->id]);
            }
        }
        if ($product instanceof Package && $typePackage == Package::TYPE_STANDARD) {
            if (isset($shopcartData[self::PACKAGE_PARAM_STANDARD][$product->id . '-' . $productFirst->id . '-' . $productSecond->id])) {
                unset($shopcartData[self::PACKAGE_PARAM_STANDARD][$product->id . '-' . $productFirst->id . '-' . $productSecond->id]);
            }
            if (isset($this->products[self::PACKAGE_PARAM_STANDARD][$product->id . '-' . $productFirst->id . '-' . $productSecond->id])) {
                unset($this->products[self::PACKAGE_PARAM_STANDARD][$product->id . '-' . $productFirst->id . '-' . $productSecond->id]);
            }
        }
        if ($product instanceof Package && $typePackage == Package::TYPE_ONE) {
            if (isset($shopcartData[self::PACKAGE_PARAM_ONE][$product->id . '-' . $productFirst->id])) {
                unset($shopcartData[self::PACKAGE_PARAM_ONE][$product->id . '-' . $productFirst->id]);
            }
            if (isset($this->products[self::PACKAGE_PARAM_ONE][$product->id . '-' . $productFirst->id])) {
                unset($this->products[self::PACKAGE_PARAM_ONE][$product->id . '-' . $productFirst->id]);
            }
        }
        if ($product instanceof Package && $typePackage == Package::TYPE_ASORTI) {
            if (isset($shopcartData[self::PACKAGE_PARAM_ASORTI][$product->id])) {
                unset($shopcartData[self::PACKAGE_PARAM_ASORTI][$product->id]);
            }
            if (isset($this->products[self::PACKAGE_PARAM_ASORTI][$product->id])) {
                unset($this->products[self::PACKAGE_PARAM_ASORTI][$product->id]);
            }
        }

        $this->setSessionShopcartData($shopcartData);
        $this->recalculateTotalValues();

        return $this;
    }

    /**
     * @return int total count
     */
    public function getTotalCount()
    {
        $shopcartData = $this->getSessionShopcartData();

        return isset($shopcartData[self::COUNT_PARAM])
            ? $shopcartData[self::COUNT_PARAM]
            : 0;
    }


    /**
     * @return int total sum of user's shopcart.
     */
    public function getTotalSum()
    {
        $shopcartData = $this->getSessionShopcartData();

        return isset($shopcartData[self::SUM_PARAM]) ? $shopcartData[self::SUM_PARAM] : 0;
    }

    /**
     * @return array
     */
    protected function getSessionShopcartData()
    {
        $shopcartData = Yii::$app->session->get(self::SHOPCART_PARAM, [
            self::PRODUCT_VOLUMES_PARAM => [],
            self::PACKAGE_PARAM_STANDARD => [],
            self::PACKAGE_PARAM_ONE => [],
            self::PACKAGE_PARAM_ASORTI => [],
            self::COUNT_PARAM => 0,
            self::SUM_PARAM => 0,
        ]);

        return $shopcartData;
    }

    /**
     * @param array $shopcartData
     * @return $this Shopcart
     */
    protected function setSessionShopcartData($shopcartData = null)
    {
        Yii::$app->session->set(self::SHOPCART_PARAM, $shopcartData);

        return $this;
    }

    /**
     * @return null|ProductVolume[] in id => ProductVolume format.
     */
    public function getShopcartProduct()
    {
        if (isset($this->product)) {
            return $this->product;
        }

        $shopcartData = $this->getSessionShopcartData();

        if (!empty($shopcartData[self::PRODUCT_VOLUMES_PARAM])) {
            $this->products[self::PRODUCT_VOLUMES_PARAM] = ProductVolume::find()
                ->where(['[[id]]' => array_keys($shopcartData[self::PRODUCT_VOLUMES_PARAM])])
                ->indexBy('id')
                ->all();
        }
        if (!empty($shopcartData[self::PACKAGE_PARAM_STANDARD])) {
            foreach ($shopcartData[self::PACKAGE_PARAM_STANDARD] as $key => $value) {
                $ids = explode('-', $key);
                $this->products[self::PACKAGE_PARAM_STANDARD][$key][] = Package::findOne(['[[id]]' => $ids[0]]);
                $this->products[self::PACKAGE_PARAM_STANDARD][$key][] = ProductVolume::findOne(['[[id]]' => $ids[1]]);
                $this->products[self::PACKAGE_PARAM_STANDARD][$key][] = ProductVolume::findOne(['[[id]]' => $ids[2]]);
            }
        }
        if (!empty($shopcartData[self::PACKAGE_PARAM_ONE])) {
            foreach ($shopcartData[self::PACKAGE_PARAM_ONE] as $key => $value) {
                $ids = explode('-', $key);
                $this->products[self::PACKAGE_PARAM_ONE][$key][] = Package::findOne(['[[id]]' => $ids[0]]);
                $this->products[self::PACKAGE_PARAM_ONE][$key][] = ProductVolume::findOne(['[[id]]' => $ids[1]]);
            }
        }
        if (!empty($shopcartData[self::PACKAGE_PARAM_ASORTI])) {
            $this->products[self::PACKAGE_PARAM_ASORTI] = Package::find()
                ->where(['[[id]]' => array_keys($shopcartData[self::PACKAGE_PARAM_ASORTI])])
                ->indexBy('id')
                ->all();
        }

        return $this->products;
    }

    /**
     * @param ProductVolume|Package $product
     * @param ProductVolume|null $productFirst
     * @param ProductVolume|null $productSecond
     * @param string|null $typePackage
     * @return int count
     */
    public function getShopcartProductCount($product, $typePackage = null, $productFirst = null, $productSecond = null)
    {
        $data = $this->getSessionShopcartData();

        if ($product instanceof ProductVolume) {
            return ArrayHelper::getValue($data, self::PRODUCT_VOLUMES_PARAM . '.' . $product->id, 0);
        }
        if ($product instanceof Package && $typePackage == Package::TYPE_STANDARD) {
            return ArrayHelper::getValue(
                $data,
                self::PACKAGE_PARAM_STANDARD . '.' . $product->id . '-' . $productFirst->id . '-' . $productSecond->id,
                0
            );
        }
        if ($product instanceof Package && $typePackage == Package::TYPE_ONE) {
            return ArrayHelper::getValue(
                $data,
                self::PACKAGE_PARAM_ONE . '.' . $product->id . '-' . $productFirst->id,
                0
            );
        }
        if ($product instanceof Package && $typePackage == Package::TYPE_ASORTI) {
            return ArrayHelper::getValue($data, self::PACKAGE_PARAM_ASORTI. '.' . $product->id, 0);
        }

        return 0;
    }

    /**
     * @param ProductVolume|Package $product.
     * @param ProductVolume|null $productFirst
     * @param ProductVolume|null $productSecond
     * @param string|null $typePackage
     * @return int sum
     * @throws NotFoundHttpException
     */
    public function getShopcartProductSum($product, $typePackage = null, $productFirst = null, $productSecond = null)
    {
        if (0 === $count = $this->getShopcartProductCount($product, $typePackage, $productFirst, $productSecond)) {
            return 0;
        }
        if ($product instanceof ProductVolume) {
            $sum = $product->price;
        }
        if ($product instanceof Package && $typePackage == Package::TYPE_STANDARD) {
            $sum = $product->price + $productFirst->price + $productSecond->price;
        }
        if ($product instanceof Package && $typePackage == Package::TYPE_ONE) {
            $sum = $product->price + $productFirst->price;
        }
        if ($product instanceof Package && $typePackage == Package::TYPE_ASORTI) {
            $sum = $product->price;
        }
        
        
        return $count * ($sum);
    }
}
