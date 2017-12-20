<?php

$form = \yii\bootstrap\ActiveForm::begin();
echo $form->field($model, 'name')->textInput();
echo $form->field($model, 'intro')->textarea();
echo $form->field($model, 'imgFile')->fileInput()->label('LOGO');
echo $form->field($model, 'sort')->textInput([]);
echo $form->field($model, 'status', ['inline' => 1])->radioList([1 => '显示', 0 => '隐藏']);
echo '<button type="submit" class="btn btn-info">提交</button>';
\yii\bootstrap\ActiveForm::end();