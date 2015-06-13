<?php
use yii\grid\GridView;
use yii\data\ActiveDataProvider;
use common\models\Upload;

/* @var $this yii\web\View */
$this->title = 'Uploads';
$this->params['breadcrumbs'][] = $this->title;

$dataProvider = new ActiveDataProvider([
    'query' => Upload::find(),
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
            'filename',
            'filesize',
            'mimetype',
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
