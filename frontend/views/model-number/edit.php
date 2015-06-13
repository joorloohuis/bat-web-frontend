<?php
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Button;
use common\models\Manufacturer;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\ModelNumber */

$this->title = 'Edit model number';
$this->params['breadcrumbs'][] = ['label' => 'Model numbers', 'url' => '/model-number'];
$this->params['breadcrumbs'][] = 'Edit';
?>
<div class="box">
<?php
$form = ActiveForm::begin([
    'id' => 'edit-model-number-form',
    'action' => 'save',
]);
?>
  <?= Html::activeHiddenInput($model, 'id') ?>
  <div class="box-body">
    <div class="form-group">
      <?= $form->field($model, 'value') ?>
      <?= $form->field($model, 'manufacturer_id')->dropDownList(ArrayHelper::map(Manufacturer::find()->orderBy('name')->all(), 'id', 'name')) ?>
    </div>
    <div class="box-footer">
      <?= Button::widget(['label' => 'Save', 'options' => ['class' => 'btn btn-primary']]) ?>
    </div>
  </div>
<?php
ActiveForm::end();
?>
</div>
