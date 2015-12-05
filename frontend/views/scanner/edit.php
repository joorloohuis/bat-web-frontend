<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Button;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\Scanner */

$this->title = 'Edit scanner';
$this->params['breadcrumbs'][] = ['label' => 'Scanners', 'url' => '/scanner'];
$this->params['breadcrumbs'][] = 'Edit';
?>
<div class="box">
<?php
$form = ActiveForm::begin([
    'id' => 'edit-scanner-form',
    'action' => 'save',
]);
?>
  <?= Html::activeHiddenInput($model, 'id') ?>
  <div class="box-body">
    <div class="form-group">
      <?= $form->field($model, 'name') ?>
      <?= $form->field($model, 'description') ?>
    </div>
    <div class="box-footer">
      <?= Button::widget(['label' => 'Save', 'options' => ['class' => 'btn btn-primary']]) ?>
      <?= Html::a('Cancel', Yii::$app->request->referrer, ['class' => 'btn btn-default']); ?>
    </div>
  </div>
<?php
ActiveForm::end();
?>
</div>
