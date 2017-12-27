<table id="example" class="table table-bordered" style="text-align: center">
    <thead>
    <tr>
        <th style="text-align: center">角色</th>
        <th style="text-align: center">描述</th>
        <th style="text-align: center">操作</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($models as $model): ?>
        <tr id="<?= $model->name ?>">
            <td><?= $model->name ?></td>
            <td><?= $model->description ?></td>
            <td><a href="<?= \yii\helpers\Url::to(['rbac/edit-role', 'name' => $model->name]) ?>"
                   class=" btn btn-warning">修改</a>
                <a   class=" btn btn-danger">删除</a></td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
<?php
$url =$url = \yii\helpers\Url::to(['rbac/delete-role']);
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