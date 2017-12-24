<!DOCTYPE html>
<HTML>
<HEAD>
    <TITLE> ZTREE DEMO </TITLE>
    <meta http-equiv="content-type" content="text/html; charset=UTF-8">
    <link rel="stylesheet" href="/zTree/css/zTreeStyle/zTreeStyle.css" type="text/css">
    <link rel="stylesheet" href="/zTree/css/demo.css" type="text/css">
    <script type="text/javascript" src="/zTree/js/jquery-1.4.4.min.js"></script>

    <script type="text/javascript" src="/zTree/js/jquery.ztree.core.js"></script>
    <SCRIPT LANGUAGE="JavaScript">
        var zTreeObj;
        // zTree 的参数配置，深入使用请参考 API 文档（setting 配置详解）
        var setting = {
            data: {
                simpleData: {
                    enable: true,
                    idKey: "id",
                    pIdKey: "pId",
                    rootPId: 0
                }
            }
        };
        // zTree 的数据属性，深入使用请参考 API 文档（zTreeNode 节点数据详解）
        var zNodes = [
            {id:1, pId:0, name: "父节点1"},
            {id:11, pId:1, name: "子节点1"},
            {id:12, pId:1, name: "子节点2"}
        ];
        $(document).ready(function(){
            zTreeObj = $.fn.zTree.init($("#treeDemo"), setting, zNodes);
        });
    </SCRIPT>
</HEAD>
<BODY>
<div>
    <ul id="treeDemo" class="ztree"></ul>
</div>
</BODY>
</HTML>


//>>>1.加载 js css
$this->registerCssFile('@web/zTree/css/zTreeStyle/zTreeStyle.css');
$this->registerJsFile('@web/zTree/js/jquery.ztree.core.js',[
'depends'=>\yii\web\JqueryAsset::className(),
]);
echo <<<HTML
<!--这是显示容器-->
<div>
    <ul id="treeDemo" class="ztree"></ul>
</div>
HTML;
$nodes = \backend\models\GoodsCategory::getNodes();
$js = <<<JS
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
}
};
// zTree 的数据属性，深入使用请参考 API 文档（zTreeNode 节点数据详解）
var zNodes ={$nodes};

zTreeObj = $.fn.zTree.init($("#treeDemo"), setting, zNodes);

JS;
$this->registerJs($js);
