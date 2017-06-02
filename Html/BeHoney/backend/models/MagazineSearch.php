<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * MagazineSearch represents the model behind the search form about `backend\models\Magazine`.
 */
class MagazineSearch extends Magazine
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['id', 'integer'],
            [['title', 'content', 'descriptionMeta', 'name'], 'string'],
            [['active', 'main'], 'safe']
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
        $query = Magazine::find();
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        $this->active = null;
        $this->main = null;
        
        $this->load($params);
        
        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'active' => $this->active,
            'main' => $this->main,
        ]);
        $query->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'content', $this->content])
            ->andFilterWhere(['like', 'name', $this->content])
            ->andFilterWhere(['like', 'descriptionMeta', $this->descriptionMeta]);

        return $dataProvider;
    }
}
