<?php
$form = \yii\bootstrap\ActiveForm::begin();
echo  $form->field($model,'username')->textInput()->label("用户名");
echo  $form->field($model,'password_hash')->passwordInput()->label('密码');
echo  $form->field($model,'email')->textInput()->label('邮箱');

echo \yii\bootstrap\Html::submitButton("添加账号",['class=>btn btn-info']);
\yii\bootstrap\ActiveForm::end();