<table class="table table-bordered" style="text-align: center">
    <tr>
        <th style="text-align: center">编号</th>
        <th style="text-align: center">名称</th>
        <th style="text-align: center">简介</th>
        <th style="text-align: center">分类</th>
        <th style="text-align: center">排序</th>
        <th style="text-align: center">状态</th>
        <th style="text-align: center">创建时间</th>
        <th style="text-align: center">操作</th>
    </tr>
    <?php foreach ($articles as $article): ?>
        <tr id="<?= $article->id ?>">
            <td><?= $article->id ?></td>
            <td><?= $article->name ?></td>
            <td><?= $article->intro ?></td>
            <td><?= $article->article_category->name ?></td>

            <td><?= $article->sort ?></td>
            <td><?= $article->status == 0 ? '隐藏' : '' ?>
                <?= $article->status == 1 ? '显示' : '' ?>
                <?= $article->status == -1 ? '删除' : '' ?></td>
            <td><?= date('Y-m-d H-m-s',$article->create_time )?></td>
            <td><a href="<?= \yii\helpers\Url::to(['article/edit', 'id' => $article->id]) ?>"
                   class=" btn btn-warning">修改</a>
                <a  class=" btn btn-danger">删除</a></td>
        </tr>

    <?php endforeach; ?>
    <tr>
    <tr>
        <td colspan="8"><a href="<?= \yii\helpers\Url::to(['article/add']) ?>" class=" btn btn-info">添加</a></td>
    </tr>
</table>
<?php
$url = \yii\helpers\Url::to(['article/delete']);
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


