<table class="table table-bordered" style="text-align: center">
    <tr>
        <th style="text-align: center">编号</th>
        <th style="text-align: center">分类</th>
        <th style="text-align: center">简介</th>
        <th style="text-align: center">操作</th>
    </tr>
    <?php foreach ($models as $model): ?>
        <tr id="<?= $model->id ?>">
            <td><?= $model->id ?></td>
            <td><?= $model->name ?></td>
            <td><?= $model->intro ?></td>

            <td><a href="<?= \yii\helpers\Url::to(['goods-category/edit', 'id' => $model->id]) ?>"
                   class=" btn btn-warning">修改</a>
                <a href="<?= \yii\helpers\Url::to(['goods-category/delete', 'id' => $model->id]) ?>"
                   class=" btn btn-danger">修改</a>
       </tr>

    <?php endforeach; ?>
    <tr>
    <tr>
        <td colspan="7"><a href="<?= \yii\helpers\Url::to(['goods-category/add']) ?>" class=" btn btn-info">添加</a>
        </td>
    </tr>
</table>
