<?php
/**
 * @var $this \Yii\web\View
 */
$form = \yii\bootstrap\ActiveForm::begin();
//====================== web uploader==============================
//>>>1,引入js cs
$this->registerCssFile('@web/webuploader/webuploader.css');
$this->registerJsFile('@web/webuploader/webuploader.js', [
    //加载文件位置 依赖jquery
    'depends' => \yii\web\JqueryAsset::className(),
]);
$upload_url = \yii\helpers\Url::to(['goods/upload1','id'=>$id]);
echo <<<HTML
<!--dom结构部分-->
<div id="uploader-demo">
    <img id="img" src="" width="100px">
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
   console.debug(response);
  //回显图片 
  var url =response.url;

 
  
 $("<tr id="+response.gid+"><td><img src="+url+"></td><td><a href='#'>删除</a></td></tr>").appendTo($('#table'))
 
});
JS;
$this->registerJs($js);
//====================== web uploader==============================

\yii\bootstrap\ActiveForm::end();
?>
<table id="table" class="table">
    <tr id="menu">
        <th>图片</th>
        <th>操作</th>
    </tr>
    <?php foreach($goods as $row):?>
        <tr id="<?=$row->id?>">
            <td><img src="<?=$row->path?>" alt=""></td>
            <td><a class="btn btn-warning" href="">删除</a></td>
        </tr>
    <?php endforeach;?>
</table>
<?php
$address=\yii\helpers\Url::to(['goods/del-gallery']);
$js1=<<<JS
    $("#table").on("click",'tr td a',function(){
       if(confirm("您确定要删除吗?")){
           //>>1.找到当前的商品id和相册id
               var id=$(this) .closest("tr").attr("id");
               var data_id=$(this).closest("tr").attr("data_id");
                $(this).closest("tr").remove();
              //发送ajax请求删除该行；
              $.getJSON('$address?id='+id+'&did='+data_id,function(data){
                  
              })
       }
    })
JS;
$this->registerJs($js1);
