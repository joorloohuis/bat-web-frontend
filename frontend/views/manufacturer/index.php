<?php
use yii\grid\GridView;
use yii\data\ActiveDataProvider;
use common\models\Manufacturer;

/* @var $this yii\web\View */
$this->title = 'Manufacturers';
$this->params['breadcrumbs'][] = $this->title;

$dataProvider = new ActiveDataProvider([
    'query' => Manufacturer::find(),
    'pagination' => [
        'pageSize' => 20,
    ],
]);
?>
<div class="box">
  <div class="box-body">

<?php
if (Yii::$app->user->can('listManufacturers')) {
    echo GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'name',
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
