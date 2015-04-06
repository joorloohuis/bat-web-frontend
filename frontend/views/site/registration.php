<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\models\RegistrationForm */

$this->title = 'Registration';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-registration">
    <div class="register-box">
      <div class="register-box-body">
        <p class="login-box-msg">Register as a user</p>
        <?php
        $form = ActiveForm::begin(['id' => 'form-registration']);
        echo $form->field($model, 'username', [
            'template' => '<div class="form-group has-feedback">{input}<span class="glyphicon glyphicon-user form-control-feedback"></span>{error}</div>',
            'inputOptions' => [
                'placeholder' => $model->getAttributeLabel('username'),
        ]])->label(false);
        echo $form->field($model, 'fullname', [
            'template' => '<div class="form-group has-feedback">{input}<span class="glyphicon glyphicon-user form-control-feedback"></span>{error}</div>',
            'inputOptions' => [
                'placeholder' => $model->getAttributeLabel('fullname'),
        ]])->label(false);
        echo $form->field($model, 'email', [
            'template' => '<div class="form-group has-feedback">{input}<span class="glyphicon glyphicon-envelope form-control-feedback"></span>{error}</div>',
            'inputOptions' => [
                'placeholder' => $model->getAttributeLabel('email'),
        ]])->label(false);
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
              <?= Html::submitButton('Register', [
                  'class' => 'btn btn-primary btn-block btn-flat',
                  'name' => 'registration-button',
              ]) ?>
            </div>
          </div>
        <?php ActiveForm::end(); ?>
        <a href="/site/login" class="text-center">I'm already registered.</a>
      </div>
    </div>

</div>
