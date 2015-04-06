<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

$this->title = 'Login';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="login-box">
  <div class="login-box-body">

    <p class="login-box-msg">Log in to start your session</p>
      <?php
      $form = ActiveForm::begin(['id' => 'login-form']);
      echo $form->field($model, 'username', [
          'template' => '<div class="form-group has-feedback">{input}<span class="glyphicon glyphicon-user form-control-feedback"></span>{error}</div>',
          'inputOptions' => [
              'placeholder' => $model->getAttributeLabel('username'),
      ]])->label(false);
      echo $form->field($model, 'password', [
          'template' => '<div class="form-group has-feedback">{input}<span class="glyphicon glyphicon-lock form-control-feedback"></span>{error}</div>',
          'inputOptions' => [
              'placeholder' => $model->getAttributeLabel('password'),
      ]])->passwordInput()->label(false);
      ?>
      <div class="row">
        <div class="col-xs-8">
          <?= $form->field($model, 'rememberMe', [
              'template' => '<div class="checkbox icheck">{input}</div>'
          ])->checkbox(['label' => 'Remember Me'], true) ?>
        </div>
        <div class="col-xs-4">
          <?= Html::submitButton('Login', [
              'class' => 'btn btn-primary btn-block btn-flat',
              'name' => 'login-button',
          ]) ?>
        </div>
      </div>
    <?php ActiveForm::end(); ?>
    <?= Html::a('I forgot my password', ['site/request-password-reset']) ?><br />
    <?= Html::a('Register a new account', ['site/registration'], ['class' => 'text-center']) ?>
  </div>
</div>

