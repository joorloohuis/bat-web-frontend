<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\Json;
use yii\helpers\ArrayHelper;
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Button;
use kartik\typeahead\Typeahead;
use common\models\Manufacturer;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\models\FirmwareForm */

$this->title = 'Edit firmware';
$this->params['breadcrumbs'][] = ['label' => 'Firmwares', 'url' => '/firmware'];
$this->params['breadcrumbs'][] = 'Edit';
// prefetch manufacturers once
$manufacturers = Json::decode(file_get_contents(Url::toRoute(['manufacturer/list'], true)));
?>
<div class="box">
<?php
$form = ActiveForm::begin([
    'id' => 'edit-firmware-form',
    'action' => 'save',
]);
?>
  <?= Html::activeHiddenInput($model, 'firmware_id') ?>
  <div class="box-body">
    <div class="form-group">
      <?= $form->field($model, 'upload_name')->textInput(['readonly' => true]) ?>
      <?= $form->field($model, 'checksum')->textInput(['readonly' => true]) ?>
      <?= $form->field($model, 'description')->textInput() ?>
      <?= $form->field($model, 'device_type')->widget(Typeahead::classname(), [
        'options' => ['placeholder' => 'Filter as you type ...'],
        'pluginOptions' => ['highlight' => true],
        'dataset' => [
          'source' => [
            'prefetch' => Url::to(['device-type/list']),
          ],
        ],
      ]) ?>
      <?= $form->field($model, 'manufacturer')->widget(Typeahead::classname(), [
        'options' => ['placeholder' => 'Filter as you type ...'],
        'pluginOptions' => ['highlight' => true],
        'dataset' => [
          'source' => [
            'local' => $manufacturers,
          ],
        ],
      ]) ?>
      <?= $form->field($model, 'odm')->widget(Typeahead::classname(), [
        'options' => ['placeholder' => 'Filter as you type ...'],
        'pluginOptions' => ['highlight' => true],
        'dataset' => [
          'source' => [
            'local' => $manufacturers,
          ],
        ],
      ]) ?>
      <?= $form->field($model, 'model_number')->widget(Typeahead::classname(), [
        'options' => ['placeholder' => 'Filter as you type ...'],
        'pluginOptions' => ['highlight' => true],
        'dataset' => [
          'source' => [
            'prefetch' => Url::to(['model-number/list']),
          ],
        ],
      ]) ?>
      <?= $form->field($model, 'chipset')->widget(Typeahead::classname(), [
        'options' => ['placeholder' => 'Filter as you type ...'],
        'pluginOptions' => ['highlight' => true],
        'dataset' => [
          'source' => [
            'prefetch' => Url::to(['chipset/list']),
          ],
        ],
      ]) ?>
      <?= $form->field($model, 'fcc_number')->textInput() ?>
      <?= $form->field($model, 'download_url')->textInput() ?>
      <?= $form->field($model, 'mac_address')->textInput() ?>
      <?= $form->field($model, 'notes')->textArea(['rows' => 6]) ?>
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
