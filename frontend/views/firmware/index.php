<?php
use Yii;
use yii\grid\GridView;
use yii\data\ActiveDataProvider;
use common\models\Firmware;
use frontend\models\UploadForm;
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Button;
use kartik\file\FileInput;

// @var $this yii\web\View
// @var $form yii\bootstrap\ActiveForm
// @var $model \common\models\UploadForm

$this->title = 'Firmwares';
$this->params['breadcrumbs'][] = $this->title;

$dataProvider = new ActiveDataProvider([
    // regular users only have access to their own data
    'query' => Yii::$app->user->can('admin') ? Firmware::find() : Firmware::findByUser(Yii::$app->user->identity->username),
    'pagination' => [
        'pageSize' => 20,
    ],
]);
?>

<div class="box">
  <div class="box-body">
<?php $form = ActiveForm::begin([
    'id' => 'upload-form',
    'action' => '/firmware/upload',
    'options' => [
        'enctype' => 'multipart/form-data',
        'class' => 'form-inline',
    ],
])?>
<?=$form->field($model = new UploadForm(), 'file')->widget(FileInput::classname(), [
    'pluginOptions' => [
        'showPreview' => false,
        'showCaption' => true,
        'showRemove' => true,
        'showUpload' => true,
        'browseLabel' =>  'Add firmware'
    ],
])?>
<?php ActiveForm::end() ?>
  </div>
</div>

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
                'attribute' => 'manufacturer_id',
                'value' => function ($model, $index, $widget) { return $model->manufacturer ? $model->manufacturer->name : ''; }
            ],
            [
                'attribute' => 'model_number_id',
                'value' => function ($model, $index, $widget) { return $model->modelNumber ? $model->modelNumber->value : ''; }
            ],
            [
                'attribute' => 'device_type_id',
                'value' => function ($model, $index, $widget) { return $model->deviceType ? $model->deviceType->name : ''; }
            ],
            [
                'attribute' => 'chipset_id',
                'value' => function ($model, $index, $widget) { return $model->chipset ? $model->chipset->value : ''; }
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
