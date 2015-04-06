<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\models\PasswordResetRequestForm */

$this->title = 'Request password reset';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="login-box">
  <div class="login-box-body">

    <p class="text-left login-box-msg">Please enter your email address. A link to reset password will be sent there.</p>
    <?php
    $form = ActiveForm::begin(['id' => 'request-password-reset-form']);
    echo $form->field($model, 'email', [
        'template' => '<div class="form-group has-feedback">{input}<span class="glyphicon glyphicon-envelope form-control-feedback"></span>{error}</div>',
        'inputOptions' => [
            'placeholder' => $model->getAttributeLabel('email'),
    ]])->label(false);
    ?>
    <div class="row">
      <div class="col-xs-8">
      </div>
      <div class="col-xs-4">
        <?= Html::submitButton('Send', [
            'class' => 'btn btn-primary btn-block btn-flat',
        ]) ?>
      </div>
    </div>
    <?php ActiveForm::end(); ?>
  </div>
</div>
