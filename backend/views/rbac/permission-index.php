<?php
$this->registerCssFile('@web/datatables/media/css/dataTables.jqueryui.css');
$this->registerJsFile('@web/datatables/media/js/jquery.dataTables.min.js',[
    'depends'=>\yii\web\JqueryAsset::className()
]);
?>

<table id="example" class="table table-bordered" style="text-align: center">
    <thead>
    <tr>
        <th style="text-align: center">名称</th>
        <th style="text-align: center">描述</th>
        <th style="text-align: center">操作</th>
    </tr>
    </thead>
  <tbody>
  <?php foreach ($models as $model): ?>
      <tr id="<?= $model->name ?>">
      <td><?= $model->name ?></td>
      <td><?= $model->description ?></td>
      <td><a href="<?= \yii\helpers\Url::to(['rbac/edit-permission', 'name' => $model->name]) ?>"
             class=" btn btn-warning">修改</a>
          <a   class=" btn btn-danger">删除</a></td>
      </tr>
  <?php endforeach; ?>
  </tbody>
</table>
<?php
$url =$url = \yii\helpers\Url::to(['rbac/delete-permission']);
$js =
    <<<JS
  $(function () {
        $('#example').DataTable({
           language: {
    "sProcessing": "处理中...",
    "sLengthMenu": "显示 _MENU_ 项结果",
    "sZeroRecords": "没有匹配结果",
    "sInfo": "显示第 _START_ 至 _END_ 项结果，共 _TOTAL_ 项",
    "sInfoEmpty": "显示第 0 至 0 项结果，共 0 项",
    "sInfoFiltered": "(由 _MAX_ 项结果过滤)",
    "sInfoPostFix": "",
    "sSearch": "搜索:",
    "sUrl": "",
    "sEmptyTable": "表中数据为空",
    "sLoadingRecords": "载入中...",
    "sInfoThousands": ",",
    "oPaginate": {
        "sFirst": "首页",
        "sPrevious": "上页",
        "sNext": "下页",
        "sLast": "末页"
    },
    "oAria": {
        "sSortAscending": ": 以升序排列此列",
        "sSortDescending": ": 以降序排列此列"
    }
} 
        });
    });
$('tr').on('click','.btn-danger',function() {
    //找到当前id
        var id = $(this).closest('tr').attr('id');
     
        if (confirm('确认删除')){
               //删除当前行
        $(this).closest('tr').remove();
            //json 传地址 id 
            $.getJSON('$url?id='+id,function(data) {
               // console.debug(data);
            if (data){
                  alert("ok")
              }else {
                  alert('删除失败')
              }
            })
        }
})








JS;
$this->registerJs($js);


