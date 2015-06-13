<?php
use yii\grid\GridView;
use yii\data\ActiveDataProvider;
use common\models\ModelNumber;

/* @var $this yii\web\View */
$this->title = 'Model numbers';
$this->params['breadcrumbs'][] = $this->title;

$dataProvider = new ActiveDataProvider([
    'query' => ModelNumber::find(),
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
            'value',
            [
                'attribute' => 'manufacturer_id',
                'value' => function ($model, $index, $widget) { return $model->manufacturer->name; }
            ],
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
