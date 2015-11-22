<?php
use yii\grid\GridView;
use yii\data\ActiveDataProvider;
use common\models\Job;

/* @var $this yii\web\View */
$this->title = 'Jobs';
$this->params['breadcrumbs'][] = $this->title;

$dataProvider = new ActiveDataProvider([
    // regular users only have access to their own data
    'query' => Yii::$app->user->can('admin') ? Job::find() : Job::findByUser(Yii::$app->user->identity->username),
    'pagination' => [
        'pageSize' => 20,
    ],
]);
?>
<div class="box">
  <div class="box-body">

<?php
if (Yii::$app->user->can('listResources')) {
    echo GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'attribute' => 'created_at',
                'format' => ['datetime', 'php:Y-m-d H:i:s']
            ],
            [
                'attribute' => 'updated_at',
                'format' => ['datetime', 'php:Y-m-d H:i:s']
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{update}'
            ],
        ],
    ]);
}
?>
  </div>
</div>
