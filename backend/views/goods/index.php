<table class="table table-bordered" style="text-align: center">
    <tr>
        <th style="text-align: center">编号</th>
        <th style="text-align: center">货号</th>
        <th style="text-align: center">名称</th>
        <th style="text-align: center">LOGO</th>

        <th style="text-align: center">商品分类</th>
        <th style="text-align: center">品牌名称</th>
        <th style="text-align: center">市场价格</th>
        <th style="text-align: center">商场价格</th>
        <th style="text-align: center">库存</th>
        <th style="text-align: center">是否在售</th>
        <th style="text-align: center">状态</th>
        <th style="text-align: center">排序</th>
        <th style="text-align: center">添加时间</th>
        <th style="text-align: center">浏览次数</th>
        <th style="text-align: center">操作</th>
    </tr>
    <?php foreach ($goods as $good): ?>
        <tr id="<?= $good->id ?>">
            <td><?= $good->id ?></td>
            <td><?= $good->sn ?></td>
            <td><?= $good->name ?></td>
            <td><?= $good->logo ?></td>
            <td><?= $good->goodsCategory->name ?></td>
            <td><?= $good->brand->name ?></td>
            <td><?= $good->market_price ?></td>
            <td><?= $good->shop_price ?></td>
            <td><?= $good->stock ?></td>
            <td><?= $good->is_on_sale == 0 ? '下架' : '' ?>
                <?= $good->is_on_sale == 1 ? '在售' : '' ?>
              </td>
            <td><?= $good->status == 0 ? '回收站' : '' ?>
                <?= $good->status == 1 ? '正常' : '' ?>
              </td>
            <td><?= $good->sort ?></td>
            <td><?= date('Y-m-d H:i:s',$good->create_time )?></td>
            <td><?= $good->view_time ?></td>
            <td>  <a href="<?= \yii\helpers\Url::to(['goods/gallery']) ?>"
                     class="glyphicon glyphicon-picture">相册</a>
                <a href="<?= \yii\helpers\Url::to(['goods/edit', 'id' => $good->id]) ?>"
                   class=" btn btn-warning">修改</a>
                <a  class=" btn btn-danger">删除</a></td>
        </tr>

    <?php endforeach; ?>
    <tr>
    <tr>
        <td colspan="15"><a href="<?= \yii\helpers\Url::to(['goods/add']) ?>" class=" btn btn-info">添加</a></td>
    </tr>
</table>
<?php
$url = \yii\helpers\Url::to(['goods/delete']);
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
               // console.debug(data)
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


