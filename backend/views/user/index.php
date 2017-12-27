<table class="table table-bordered">
    <tr>
        <td>id</td>
        <td>用户名</td>
        <td>email</td>
        <td>最后登录时间</td>
        <td>最后登录ip</td>
        <td>操作</td>
    </tr>
    <?php foreach ($users as $row):?>
        <tr id="">
            <td><?=$row->id?></td>
            <td><?=$row->username?></td>
            <td><?=$row->email?></td>
            <td><?=date('Y-m-d H:i:s',$row->last_login_time)?></td>
            <td><?=$row->last_login_ip?></td>
            <td  style="text-align: center">
                <a class="btn btn-warning" href="<?=\yii\helpers\Url::to(['user/edit', 'id' => $row->id]) ?>">修改</a>
                <a class="btn btn-danger" href="<?=\yii\helpers\Url::to(['user/delete', 'id' => $row->id]) ?>">删除</a></td>

        </tr>
    <?php endforeach;?>
    <tr>
        <td colspan="9" style="text-align: center">
            <a class="btn btn-primary" href="<?=\yii\helpers\Url::to(['user/add'])?>">添加</a></td>
    </tr>
</table>
