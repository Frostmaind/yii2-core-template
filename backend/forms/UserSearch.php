<?php

namespace backend\forms;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use core\entities\User\User;
use yii\helpers\ArrayHelper;

/**
 * UserSearch represents the model behind the search form of `core\entities\User\User`.
 */
class UserSearch extends Model
{
    public $id;
    public $status;
    public $created_from;
    public $created_to;
    public $username;
    public $email;
    public $role;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'status'], 'integer'],
            [['username', 'email', 'role'], 'safe'],
            [['created_from', 'created_to'], 'date', 'format' => 'php:Y-m-d'],
        ];
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
        $query = User::find()->alias('u');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'u.id' => $this->id,
            'u.status' => $this->status,
        ]);

        if (!empty($this->role)) {
            $query->innerJoin('{{%auth_assignments%}} a', 'a.user_id = u.id');
            $query->andWhere(['a.item_name' => $this->role]);
        }

        $query
            ->andFilterWhere(['like', 'u.username', $this->username])
            ->andFilterWhere(['like', 'u.email', $this->email])
            ->andFilterWhere(['>=', 'u.created_at', $this->created_from ? strtotime($this->created_from) . ' 00:00:00' : null])
            ->andFilterWhere(['<=', 'u.created_at', $this->created_to ? strtotime($this->created_to) . ' 23:59:59' : null]);

        return $dataProvider;
    }

    public function rolesList(): array
    {
        return ArrayHelper::map(\Yii::$app->authManager->getRoles(), 'name', 'description');
    }

}
