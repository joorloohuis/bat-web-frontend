<?php
use yii\grid\GridView;
use yii\data\ActiveDataProvider;
use common\models\Firmware;

/* @var $this yii\web\View */
$this->title = 'Firmwares';
$this->params['breadcrumbs'][] = $this->title;

$dataProvider = new ActiveDataProvider([
    'query' => Firmware::find(),
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
                'attribute' => 'device_type_id',
                'value' => function ($model, $index, $widget) { return $model->deviceType ? $model->deviceType->name : ''; }
            ],
            [
                'attribute' => 'manufacturer_id',
                'value' => function ($model, $index, $widget) { return $model->manufacturer ? $model->manufacturer->name : ''; }
            ],
            [
                'attribute' => 'model_number_id',
                'value' => function ($model, $index, $widget) { return $model->modelNumber ? $model->modelNumber->value : ''; }
            ],
            [
                'attribute' => 'upload_id',
                'value' => function ($model, $index, $widget) { return $model->upload ? $model->upload->filename : ''; }
            ],
            [
                'attribute' => 'created_by',
            ],
            [
                'attribute' => 'created_at',
                'format' => ['datetime', 'php:Y-m-d H:i:s']
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{update} {delete}'
            ],
        ],
    ]);
}
?>
  </div>
</div>
