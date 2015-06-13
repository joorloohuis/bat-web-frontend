<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Button;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\Upload */

$this->title = 'Edit upload';
$this->params['breadcrumbs'][] = ['label' => 'Uploads', 'url' => '/upload'];
$this->params['breadcrumbs'][] = 'Edit';
?>
<div class="box">
<?php
$form = ActiveForm::begin([
    'id' => 'edit-upload-form',
    'action' => 'save',
]);
?>
  <?= Html::activeHiddenInput($model, 'id') ?>
  <div class="box-body">
    <div class="form-group">
      <?= $form->field($model, 'filename')->textInput(['readonly' => true]) ?>
      <?= $form->field($model, 'filesize')->textInput(['readonly' => true]) ?>
      <?= $form->field($model, 'checksum')->textInput(['readonly' => true]) ?>
      <?= $form->field($model, 'mimetype') ?>
      <?= $form->field($model, 'description')->textarea() ?>
    </div>
    <div class="box-footer">
      <?= Button::widget(['label' => 'Save', 'options' => ['class' => 'btn btn-primary']]) ?>
    </div>
  </div>
<?php
ActiveForm::end();
?>
</div>
