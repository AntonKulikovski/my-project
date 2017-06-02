<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * PackageSearch represents the model behind the search form about `backend\models\Package`.
 */
class PackageSearch extends Package
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'position', 'active'], 'integer'],
            [['name', 'slug', 'title', 'type', 'price', 'tags'], 'string'],
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
        $tableNamePackage = Package::tableName();
        $tableNameTag = Tag::tableName();
        $query = Package::find()
            ->joinWith([
                'packageTags',
                'packageTags.tag',
            ]);

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        $this->type = null;
        $this->active = null;

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            "$tableNamePackage.[[id]]" => $this->id,
            "$tableNamePackage.[[position]]" => $this->position,
            "$tableNamePackage.[[type]]" => $this->type,
            "$tableNamePackage.[[price]]" => $this->price,
            "$tableNamePackage.[[active]]" => $this->active,
            "$tableNameTag.[[id]]" => $this->tags,
        ]);

        $query->andFilterWhere(['like', "$tableNamePackage.[[name]]", $this->name])
            ->andFilterWhere(['like', "$tableNamePackage.[[slug]]", $this->slug])
            ->andFilterWhere(['like', "$tableNamePackage.[[title]]", $this->title]);

        return $dataProvider;
    }
}
