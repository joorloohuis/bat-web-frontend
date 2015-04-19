<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Button;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\DeviceType */

$this->title = 'Edit device type';
$this->params['breadcrumbs'][] = ['label' => 'Device Types', 'url' => '/device_type'];
$this->params['breadcrumbs'][] = 'Edit';
?>
<div class="box">
<?php
$form = ActiveForm::begin([
    'id' => 'edit-device-type-form',
    'action' => 'save',
]);
?>
  <?= Html::activeHiddenInput($model, 'id') ?>
  <div class="box-body">
    <div class="form-group">
      <?= $form->field($model, 'name') ?>
    </div>
    <div class="box-footer">
      <?= Button::widget(['label' => 'Save', 'options' => ['class' => 'btn btn-primary']]) ?>
    </div>
  </div>
<?php
ActiveForm::end();
?>
</div>
