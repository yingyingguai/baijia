<?php
$form = \yii\bootstrap\ActiveForm::begin();
echo $form->field($model, 'name')->textInput();

echo $form->field($model, 'logo')->hiddenInput();

//>>>1.加载 js css
$this->registerCssFile('@web/zTree/css/zTreeStyle/zTreeStyle.css');
$this->registerJsFile('@web/zTree/js/jquery.ztree.core.js', [
    'depends' => \yii\web\JqueryAsset::className(),
]);
$this->registerCssFile('@web/webuploader/webuploader.css');
$this->registerJsFile('@web/webuploader/webuploader.js', [
    //加载文件位置 依赖jquery
    'depends' => \yii\web\JqueryAsset::className(),
]);

$upload_url = \yii\helpers\Url::to(['goods/uploads']);
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
echo $form->field($model, 'goods_category_id')->hiddenInput();
//==============================ztree============================================

echo <<<HTML
<!--这是显示容器-->
<div>
    <ul id="treeDemo" class="ztree"></ul>
</div>
HTML;
$nodes = \backend\models\GoodsCategory::getNodes();
$js = <<<JS
//===========================LOGO==============================
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
 console.debug(response)
  //回显图片 
  $('#img').attr('src',response.url);
  
  $('#goods-logo').val(response.url);
});


//===========================LOGO==============================


        var zTreeObj;
        // zTree 的参数配置，深入使用请参考 API 文档（setting 配置详解）
        var setting = {
            data: {
                simpleData: {
                    enable: true,
                    idKey: "id",
                    pIdKey: "parent_id",
                    rootPId: 0
                }
            },
            	callback: {
		            onClick: function(event,treeId,treeNode) {
		                //节点被点击 获取id 赋值给 $("#goodscategory-parent_id")
		       $("#goods-goods_category_id").val(treeNode.id) ;
		            }
	            }
        };
                // zTree 的数据属性，深入使用请参考 API 文档（zTreeNode 节点数据详解）
                    var zNodes ={$nodes};
            zTreeObj = $.fn.zTree.init($("#treeDemo"), setting, zNodes);
             //展开所有节点 
            zTreeObj.expandAll(true);
            //节点选中 用来回显 
            var node = zTreeObj.getNodeByParam("id",'{$model->goods_category_id}',null);
  zTreeObj.selectNode(node);
JS;
$this->registerJs($js);
//==============================ztree============================================
echo $form->field($model, 'brand_id')->dropDownList($brands);
echo $form->field($model, 'market_price')->textInput();
echo $form->field($model, 'shop_price')->textInput();
echo $form->field($model, 'stock')->textInput();
echo $form->field($model, 'is_on_sale', ['inline' => 1])->radioList([1 => '正常', 0 => '隐藏']);
echo $form->field($model, 'status', ['inline' => 1])->radioList([1 => '正常', 0 => '回收站']);
echo $form->field($model, 'sort')->textInput();
echo $form->field($model,'content')->widget(\kucha\ueditor\UEditor::className());
echo '<button type="submit" class="btn btn-primary">提交</button>';
\yii\bootstrap\ActiveForm::end();