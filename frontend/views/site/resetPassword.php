<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\models\ResetPasswordForm */

$this->title = 'Reset password';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-reset-password">
    <div class="register-box">
      <div class="register-box-body">
        <p class="login-box-msg">Please choose your new password:</p>
        <?php
        $form = ActiveForm::begin(['id' => 'reset-password-form']);
        echo $form->field($model, 'password', [
            'template' => '<div class="form-group has-feedback">{input}<span class="glyphicon glyphicon-lock form-control-feedback"></span>{error}</div>',
            'inputOptions' => [
                'placeholder' => $model->getAttributeLabel('password'),
        ]])->passwordInput()->label(false);
        echo $form->field($model, 'repeatpassword', [
            'template' => '<div class="form-group has-feedback">{input}<span class="glyphicon glyphicon-check form-control-feedback"></span>{error}</div>',
            'inputOptions' => [
                'placeholder' => $model->getAttributeLabel('repeatpassword'),
        ]])->passwordInput()->label(false);
        ?>
          <div class="row">
            <div class="col-xs-8">
            </div>
            <div class="col-xs-4">
              <?= Html::submitButton('Save', [
                  'class' => 'btn btn-primary btn-block btn-flat',
              ]) ?>
            </div>
          </div>
        <?php ActiveForm::end(); ?>
      </div>
    </div>

</div>

