<?php
use yii\grid\GridView;
use yii\data\ActiveDataProvider;
use common\models\Chipset;

/* @var $this yii\web\View */
$this->title = 'Chipsets';
$this->params['breadcrumbs'][] = $this->title;

$dataProvider = new ActiveDataProvider([
    'query' => Chipset::find(),
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
