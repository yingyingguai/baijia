<?php
$form = \yii\bootstrap\ActiveForm::begin();
echo  $form->field($model,'old_password')->passwordInput()->label('旧密码');
echo  $form->field($model,'ne_password')->passwordInput()->label('新密码');
echo  $form->field($model,'re_password')->passwordInput()->label('确认密码');
echo \yii\bootstrap\Html::submitButton("修改密码",['class=>btn btn-info']);
\yii\bootstrap\ActiveForm::end();