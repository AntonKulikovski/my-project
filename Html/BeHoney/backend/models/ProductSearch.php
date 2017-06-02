<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * ProductSearch represents the model behind the search form about `backend\models\Product`.
 */
class ProductSearch extends Product
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'position', 'categoryId'], 'integer'],
            [['name', 'slug', 'title'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $tableNameProduct = Product::tableName();
        $tableNameCategory = Category::tableName();
        $query = Product::find()->joinWith([
            'category']);

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            "$tableNameProduct.[[id]]" => $this->id,
            "$tableNameCategory.[[id]]" => $this->categoryId,
        ]);

        $query->andFilterWhere(['like', "$tableNameProduct.[[name]]", $this->name])
            ->andFilterWhere(['like', "$tableNameProduct.slug", $this->slug])
            ->andFilterWhere(['like', "$tableNameProduct.title", $this->description]);

        return $dataProvider;
    }
}
