<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Button;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\User */

$this->title = 'Edit manufacturer';
$this->params['breadcrumbs'][] = 'Profile';
?>
<div class="box">
<?php
$form = ActiveForm::begin([
    'id' => 'profile-form',
]);
?>
  <?= Html::activeHiddenInput($model, 'id') ?>
  <div class="box-body">
    <div class="form-group">
      <?= $form->field($model, 'username')->label('User name')->input('text', ['disabled' => true]) ?>
      <?= $form->field($model, 'fullname')->label('Full name') ?>
      <?= $form->field($model, 'email')->label('Email address') ?>
    </div>
    <div class="box-footer">
      <?= Button::widget(['label' => 'Save', 'options' => ['class' => 'btn btn-primary']]) ?>
    </div>
  </div>
<?php
ActiveForm::end();
?>
</div>
