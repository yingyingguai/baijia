<table class="table table-bordered" style="text-align: center">
    <tr>
        <th style="text-align: center">编号</th>
        <th style="text-align: center">分类</th>
        <th style="text-align: center">简介</th>
        <th style="text-align: center">排序</th>
        <th style="text-align: center">状态</th>
        <th style="text-align: center">操作</th>
    </tr>
    <?php foreach ($categorys as $category): ?>
        <tr id="<?= $category->id ?>">
            <td><?= $category->id ?></td>
            <td><?= $category->name ?></td>
            <td><?= $category->intro ?></td>
            <td><?= $category->sort ?></td>
            <td><?= $category->status == 0 ? '隐藏' : '' ?>
                <?= $category->status == 1 ? '显示' : '' ?>
                <?= $category->status == -1 ? '删除' : '' ?></td>
            <td><a href="<?= \yii\helpers\Url::to(['article-category/edit', 'id' => $category->id]) ?>"
                   class=" btn btn-warning">修改</a>
                <a  class=" btn btn-danger">删除</a></td>
        </tr>

    <?php endforeach; ?>
    <tr>
    <tr>
        <td colspan="7"><a href="<?= \yii\helpers\Url::to(['article-category/add']) ?>" class=" btn btn-info">添加</a>
        </td>
    </tr>
</table>

<?php
$url = \yii\helpers\Url::to(['article-category/delete']);
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