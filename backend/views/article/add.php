<?php
$form = \yii\bootstrap\ActiveForm::begin();
echo $form->field($model, 'name')->textInput();
echo $form->field($model, 'intro')->textInput();
echo $form->field($model, 'article_category_id')->dropDownList($options);
echo $form->field($model, 'sort')->textInput();
echo $form->field($model, 'status', ['inline' => 1])->radioList([1 => '正常', 0 => '隐藏']);
echo $form->field($model,'content')->widget(\common\widgets\ueditor\Ueditor::className());
echo '<button type="submit" class="btn btn-primary">提交</button>';
\yii\bootstrap\ActiveForm::end();