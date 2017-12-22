<?php
/**
 * @var $this \Yii\web\View
 */
$form = \yii\bootstrap\ActiveForm::begin();
echo $form->field($model, 'name')->textInput();
echo $form->field($model, 'intro')->textarea();
echo $form->field($model, 'logo')->hiddenInput();

//====================== web uploader==============================
//>>>1,引入js cs
$this->registerCssFile('@web/webuploader/webuploader.css');
$this->registerJsFile('@web/webuploader/webuploader.js', [
    //加载文件位置 依赖jquery
    'depends' => \yii\web\JqueryAsset::className(),
]);
$upload_url = \yii\helpers\Url::to(['brand/upload']);
echo <<<HTML
<!--dom结构部分-->
<div id="uploader-demo">
    <img id="img" src="$model->logo" width="100px">
    <!--用来存放item-->
    <div id="fileList" class="uploader-list"></div>
    <div id="filePicker">选择图片</div>
</div>
<img id="img" width="100px">
HTML;
$js = <<<JS
// 初始化Web Uploader
var uploader = WebUploader.create({

    // 选完文件后，是否自动上传。
    auto: true,
    // swf文件路径 这个不用
   swf: '/webuploader/Uploader.swf',
    // 文件接收服务端。
    server: '{$upload_url}',
    // 选择文件的按钮。可选。
    // 内部根据当前运行是创建，可能是input元素，也可能是flash.
    pick: '#filePicker',
    // 只允许选择图片文件。
    accept: {
        title: 'Images',
        extensions: 'gif,jpg,jpeg,bmp,png',
        mimeTypes: 'image/*'
    }  
});
// 文件上传成功，给item添加成功class, 用样式标记上传成功。
uploader.on( 'uploadSuccess', function( file ,response) {
    // $( '#'+file.id ).addClass('upload-state-done');
  //  console.debug(1)
  //回显图片 
  $('#img').attr('src',response.url);
  
  $('#brand-logo').val(response.url);
});
JS;
$this->registerJs($js);
//====================== web uploader==============================
echo $form->field($model, 'sort')->textInput([]);
echo $form->field($model, 'status', ['inline' => 1])->radioList([1 => '显示', 0 => '隐藏']);
echo '<button type="submit" class="btn btn-info">提交</button>';
\yii\bootstrap\ActiveForm::end();