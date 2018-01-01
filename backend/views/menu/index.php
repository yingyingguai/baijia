<table class="table table-bordered" style="text-align: center">
    <tr>
        <th style="text-align: center">名称</th>
        <th style="text-align: center">路由</th>
        <th style="text-align: center">排序</th>
        <th style="text-align: center">操作</th>
    </tr>
    <?php foreach ($model as $row): ?>
        <tr id="<?= $row->id ?>">
            <td><?= $row->label ?></td>
            <td><?= $row->url ?></td>
            <td><?= $row->sort ?></td>
            <td><a href="<?= \yii\helpers\Url::to(['menu/edit', 'id' => $row->id]) ?>"
                   class=" btn btn-warning">修改</a>
                <a  class=" btn btn-danger">删除</a></td>
        </tr>
    <?php endforeach; ?>
    <tr>
    <tr>
        <td colspan="7"><a href="<?= \yii\helpers\Url::to(['menu/add']) ?>" class=" btn btn-info">添加</a></td>
    </tr>
</table>
<?php
$url = \yii\helpers\Url::to(['menu/delete']);
$js =
    <<<JS
$('tr').on('click','.btn-danger',function() {
    //找到当前id
        var id = $(this).closest('tr').attr('id');
     
        if (confirm('确认删除')){
               //删除当前行
        $(this).closest('tr').remove();
            //json 传地址 id 
            $.getJSON('$url?id='+id,function(data) {
                console.debug(data);
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


