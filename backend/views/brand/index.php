<table class="table table-bordered" style="text-align: center">
    <tr>
        <th style="text-align: center">编号</th>
        <th style="text-align: center">品牌</th>
        <th style="text-align: center">简介</th>
        <th style="text-align: center">LOGO</th>
        <th style="text-align: center">排序</th>
        <th style="text-align: center">状态</th>
        <th style="text-align: center">操作</th>
    </tr>
    <?php foreach ($brands as $brand): ?>
    <tr id="<?= $brand->id ?>">

        <td><?= $brand->id ?></td>
        <td><?= $brand->name ?></td>
        <td><?= $brand->intro ?></td>
        <td><img src="<?= $brand->logo ?>" alt="" width="20px"></td>
        <td><?= $brand->sort ?></td>
        <td><?= $brand->status == 0 ? '隐藏' : '' ?>
            <?= $brand->status == 1 ? '显示' : '' ?>
            <?= $brand->status == -1 ? '删除' : '' ?></td>
        <td><a href="<?= \yii\helpers\Url::to(['brand/edit', 'id' => $brand->id]) ?>"
               class=" btn btn-warning">修改</a>
            <a  class=" btn btn-danger">删除</a></td>
    </tr>

<?php endforeach; ?>
<tr>
<tr>
    <td colspan="7"><a href="<?= \yii\helpers\Url::to(['brand/add']) ?>" class=" btn btn-danger">添加</a></td>
</tr>
</table>
<?php
$url = \yii\helpers\Url::to(['brand/delete']);
$js =
    <<<JS
$('tr').on('click','.btn-danger',function() {
    //找到当前id
        var id = $(this).closest('tr').attr('id');
        //删除当前行
        $(this).closest('tr').remove();
        if (confirm('确认删除')){
            //json 传地址 id 
            $.getJSON('$url?id='+id,function(data) {
                console.debug(data)
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


