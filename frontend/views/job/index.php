<?php
use yii\grid\GridView;
use yii\data\ActiveDataProvider;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
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
                'attribute' => 'description',
            ],
            [
                'attribute' => 'firmware',
                'value' => function ($model, $index, $widget) { return $model->firmware && $model->firmware->description ? $model->firmware->description : ''; }
            ],
            [
                'attribute' => 'scanner',
                'value' => function ($model, $index, $widget) { return $model->scanner && $model->scanner->name ? $model->scanner->name : ''; }
            ],
            [
                'attribute' => 'status',
                'value' => function ($model, $index, $widget) { return $model->getCurrentStatus(); }
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
                'template' => '{update} {delete} {schedule} {reset} {view}',
                'buttons' => [
                    'delete' => function ($url, $model, $key) {
                        if ($model->canDelete()) {
                            return Html::a('<span class="glyphicon glyphicon-trash"></span>', [
                                '//job/delete',
                                'id' => $model->id
                            ], [
                                'data-pjax' => 0,
                                'data-method' => 'post',
                                'data-confirm' => 'Are you sure you want to delete this item?',
                                'aria-label' => "Delete",
                                'title' => 'Delete',
                            ]);
                        }
                        return '<span class="glyphicon glyphicon-trash text-muted"></span>';
                    },
                    'schedule' => function ($url, $model, $key) {
                        if ($model->canSchedule()) {
                            return Html::a('<span class="glyphicon glyphicon-time"></span>', [
                                '//job/schedule',
                                'id' => $model->id
                            ], [
                                'data-pjax' => 0,
                                'data-method' => 'post',
                                'data-confirm' => 'Are you sure you want to (re)schedule this item?',
                                'aria-label' => "Schedule",
                                'title' => 'Schedule',
                            ]);
                        }
                        return '<span class="glyphicon glyphicon-time text-muted"></span>';
                    },
                    'reset' => function ($url, $model, $key) {
                        if ($model->canReset()) {
                            return Html::a('<span class="glyphicon glyphicon-remove-circle"></span>', [
                                'reset',
                                'id' => $model->id
                            ], [
                                'data-pjax' => 0,
                                'data-method' => 'post',
                                'data-confirm' => 'Are you sure you want to reset this item and clear its history?',
                                'aria-label' => "Reset",
                                'title' => 'Reset',
                            ]);
                        }
                        return '<span class="glyphicon glyphicon-remove-circle text-muted"></span>';
                    },
                ]
            ],
        ],
    ]);
}
?>
  </div>
</div>
