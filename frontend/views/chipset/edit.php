<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Button;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\Chipset */

$this->title = 'Edit chipset';
$this->params['breadcrumbs'][] = ['label' => 'Chipsets', 'url' => '/chipset'];
$this->params['breadcrumbs'][] = 'Edit';
?>
<div class="box">
<?php
$form = ActiveForm::begin([
    'id' => 'edit-chipset-form',
    'action' => 'save',
]);
?>
  <?= Html::activeHiddenInput($model, 'id') ?>
  <div class="box-body">
    <div class="form-group">
      <?= $form->field($model, 'value') ?>
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
