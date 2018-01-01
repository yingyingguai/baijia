<?php
$form = \yii\bootstrap\ActiveForm::begin();
echo  $form->field($model,'username')->textInput()->label("用户名");
echo  $form->field($model,'email')->textInput()->label('邮箱');
echo $form->field($model, 'status', ['inline' => 1])->radioList([1 => '启用', 0 => '禁用']);
echo  $form->field($model,'roles')->checkboxList($roles)->label('角色');

echo \yii\bootstrap\Html::submitButton("修改账号",['class=>btn btn-primary']);
\yii\bootstrap\ActiveForm::end();