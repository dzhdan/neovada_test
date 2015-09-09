<?php

namespace app\models\search;

use app\models\User;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Order;

/**
 * OrderSearch represents the model behind the search form about `app\models\Order`.
 */
class OrderSearch extends Order
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'deleted', 'buyer_id'], 'integer'],
            [['created_at'], 'safe'],
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
        $user = Yii::$app->user->identity;
        $query = Order::find()
            ->joinWith('orderItems')
            ->joinWith('buyer')
            ->where(['{{%orders}}.deleted' => false]);

        if (Yii::$app->user->can(User::ROLE_USER)) {
            $query->andWhere(['buyer_id' => $user->id]);
        }

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $dataProvider->setSort([

            'attributes' => [
                'id',
                'buyer_id' => [
                    'asc' => ['{{%users}}.email' => SORT_ASC, '{{%users}}.email' => SORT_ASC],

                ],
            ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'deleted' => $this->deleted,
            'buyer_id' => $this->buyer_id,
            'created_at' => $this->created_at,
        ]);

        return $dataProvider;
    }
}
