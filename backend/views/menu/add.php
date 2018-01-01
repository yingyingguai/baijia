<?php
$form = \yii\bootstrap\ActiveForm::begin();
echo  $form->field($model,'label')->textInput()->label("名称");
echo  $form->field($model,'parent_id')->dropDownList($menu_options,['prompt'=>'--请选择上级菜单--'])->label('上级菜单');
echo  $form->field($model,'url')->dropDownList($options,['prompt'=>'--请选择路由--'])->label('地址(路由)');
echo  $form->field($model,'sort')->textInput()->label('排序');
echo \yii\bootstrap\Html::submitButton("提交",['class=>btn btn-primary']);
\yii\bootstrap\ActiveForm::end();
