<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Button;
use common\models\Scanner;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\Job */

$this->title = 'Edit job';
$this->params['breadcrumbs'][] = ['label' => 'Jobs', 'url' => '/job'];
$this->params['breadcrumbs'][] = 'Edit';
?>
<div class="box">
<?php
$form = ActiveForm::begin([
    'id' => 'edit-job-form',
    'action' => 'save',
]);
?>
<?= Html::activeHiddenInput($model, 'id') ?>

  <div class="box-body">
    <div class="form-group">
      <?= $form->field($model->firmware, 'description')->label('Firmware')->textInput(['readonly' => true]) ?>
      <?= $form->field($model, 'description')->textInput() ?>
      <?= $form->field($model, 'scanner_id')->dropdownList(Scanner::find()->select(['name', 'id'])->indexBy('id')->column(), ['prompt'=>'Select scanner']); ?>
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
