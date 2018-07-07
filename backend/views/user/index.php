<?php

use yii\helpers\Html;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use kartik\date\DatePicker;
use yii\helpers\ArrayHelper;
use core\entities\User\User;
use core\helpers\UserHelper;

/* @var $this yii\web\View */
/* @var $searchModel backend\forms\UserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Users';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-index">

    <p>
        <?= Html::a('Create', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <div class="box">
        <div class="box-body">
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'columns' => [
                    'id',
                    [
                        'attribute' => 'created_at',
                        'filter' => DatePicker::widget([
                            'model' => $searchModel,
                            'attribute' => 'created_from',
                            'attribute2' => 'created_to',
                            'type' => DatePicker::TYPE_RANGE,
                            'separator' => '-',
                            'pluginOptions' => [
                                'todayHighlight' => true,
                                'autoclose' => true,
                                'format' => 'yyyy-mm-dd'
                            ]
                        ]),
                        'format' => 'datetime',
                    ],
                    'username',
                    'email:email',
                    [
                        'attribute' => 'role',
                        'value' => function(User $user) {
                            return implode(', ', ArrayHelper::getColumn(Yii::$app->authManager->getRolesByUser($user->id), 'description'));
                        },
                        'format' => 'raw',
                        'filter' => $searchModel->rolesList(),
                    ],
                    [
                        'attribute' => 'status',
                        'filter' => UserHelper::statusList(),
                        'value' => function(User $user) {
                            return UserHelper::statusLabel($user->status);
                        },
                        'format' => 'raw',
                    ],
                    ['class' => ActionColumn::class],
                ],
            ]); ?>
        </div>
    </div>
</div>
