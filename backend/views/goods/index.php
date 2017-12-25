<form class="form-inline" action="index" method="get" >
    <div class="form-group">
        <input type="text" class="form-control" name="sn" id="exampleInputName2" placeholder="货号">
    </div>
    <div class="form-group">
        <input type="text" class="form-control" name="keyword" id="exampleInputEmail2" placeholder="商品名">
    </div><div class="form-group">
        <input type="text" class="form-control" name="shop_price" id="exampleInputEmail2" placeholder="价格">
    </div>
    <button type="submit" class="btn  glyphicon glyphicon-search">搜索</button>
</form>

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
            <td><img width="100px" src="<?= $good->logo ?>" ></td>
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
            <td>  <a href="<?= \yii\helpers\Url::to(['goods/gallery','id' => $good->id]) ?>"
                     class="glyphicon glyphicon-picture">相册</a>


                <a href="<?= \yii\helpers\Url::to(['goods/edit', 'id' => $good->id]) ?>"
                   class=" btn btn-warning">修改</a>
        </tr>

    <?php endforeach; ?>

    <tr>
        <td colspan="15"><a href="<?= \yii\helpers\Url::to(['goods/add']) ?>" class=" btn btn-info">添加</a></td>
    </tr>
</table>
<tr>

    <?=\yii\widgets\LinkPager::widget([
        'pagination'=>$pager,
        'nextPageLabel'=>'下一页',
        'prevPageLabel'=>'上一页',
    ])?>
</tr>



