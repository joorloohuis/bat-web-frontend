<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Button;
use common\models\Scanner;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\Job */

$this->title = $model->description ?: 'View job';
$this->params['breadcrumbs'][] = ['label' => 'Jobs', 'url' => '/job'];
$this->params['breadcrumbs'][] = 'View';
?>
<div class="box">
  <div class="box-header">
    <dl>
      <dt>Firmware</dt>
      <dd><?= $model->firmware ? $model->firmware->description : '' ?></dd>
      <dt>Scanner</dt>
      <dd><?= $model->scanner ? $model->scanner->name : '' ?><br /><small><?= $model->scanner ? $model->scanner->description : '' ?></small></dd>
      <dt>Current status</dt>
      <dd><?= $model->getCurrentStatus() ?> (<?= date('c', $model->getCurrentStatus()->created_at) ?>)</dd>
    </dl>
  </div>
  <div class="box-body">
  <h2>Report</h2>
  <div>Download: <?= $model->report_url ? Html::a('full report', $model->report_url, ['target' => '_blank']) : 'not available' ?></div>
  <h3>Summary</h3>
  <div><?= $model->report_url ? nl2br($model->report) : '' ?></div>
</div>
