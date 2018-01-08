<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
    <title>填写核对订单信息</title>
    <link rel="stylesheet" href="<?=Yii::getAlias('@web')?>/style/base.css" type="text/css">
    <link rel="stylesheet" href="<?=Yii::getAlias('@web')?>/style/global.css" type="text/css">
    <link rel="stylesheet" href="<?=Yii::getAlias('@web')?>/style/header.css" type="text/css">
    <link rel="stylesheet" href="<?=Yii::getAlias('@web')?>/style/fillin.css" type="text/css">
    <link rel="stylesheet" href="<?=Yii::getAlias('@web')?>/style/footer.css" type="text/css">

    <script type="text/javascript" src="<?=Yii::getAlias('@web')?>/js/jquery-1.8.3.min.js"></script>
    <script type="text/javascript" src="<?=Yii::getAlias('@web')?>/js/cart2.js"></script>

</head>




<!-- 主体部分 start -->
<div class="fillin w990 bc mt15">
    <div class="fillin_hd">
        <h2>填写并核对订单信息</h2>
    </div>
    <?php $total=[]; ?>
    <form action="/order/add-order" method="post">
        <div class="fillin_bd">

            <!-- 收货人信息  start-->
            <div class="address">
                <h3>收货人信息</h3>
                <?php foreach ($address as $v):?>
                    <div class="address_info">
                        <p><input type="radio" value="<?=$v->id?>" name="address_id"/><?=$v->name?>
                            <?=$v->province?><?=$v->city?><?=$v->area?><?=$v->address?> </p>
                    </div>
                <?php endforeach;?>

            </div>
            <!-- 收货人信息  end-->

            <!-- 配送方式 start -->
            <div class="delivery">
                <h3>送货方式 </h3>
                <div class="delivery_select">
                    <table>
                        <thead>
                        <tr>
                            <th class="col1">送货方式</th>
                            <th class="col2">运费</th>
                            <th class="col3">运费标准</th>
                        </tr>
                        </thead>
                        <tbody id="xgx">
                        <?php foreach ($rows as $key=>$row):?>
                            <tr <?=$key==1?'class="cur"':''?>>
                                <td>
                                    <input type="radio" name="delivery" checked="checked" value="<?=$key?>" /><?=$row['name']?>

                                </td>
                                <td class="xgx2"><em><?=$row['price']?></em></td>
                                <td><?=$row['detail']?></td>
                            </tr>
                        <?php endforeach;?>
                        </tbody>
                    </table>

                </div>
            </div>
            <div class="pay">
                <h3>支付方式 </h3>
                <div  class="pay_select">

                    <table>
                        <?php foreach ($datas as $key1=>$data):?>
                            <tr <?=$key1==1?'class="cur"':''?>>
                                <td class="col1"><input type="radio" name="pay" value="<?=$key1?>"/><?=$data['name']?></td>
                                <td class="col2"><?=$data['content']?></td>
                            </tr>
                        <?php endforeach;?>
                    </table>

                </div>
            </div>
            <div class="goods">
                <h3>商品清单</h3>
                <table>
                    <thead>
                    <tr>
                        <th class="col1">商品</th>
                        <th class="col3">价格</th>
                        <th class="col4">数量</th>
                        <th class="col5">小计</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php $count=0;
                    $price=0;//总金额变量
                    ?>
                    <?php foreach($goods as $good):?>
                        <tr>

                            <td class="col1">
                                <a href="">
                                    <?php
                                    if(substr($good->logo,0,7)=='http://'){

                                        echo  "<img src='$good->logo' alt='' />";
                                    }else{
                                        echo  "<img src='http://admin.yiishop.com$good->logo' alt='' />";
                                    }
                                    ?>
                                </a>
                                <strong>
                                    <a href=""><?=$good->name?></a></strong></td>
                            <td class="col3">￥<?=$good->shop_price?></td>
                            <td class="col4"><?=$amount[$good->id]?></td>
                            <?php $count+=$amount[$good->id]?>
                            <td class="col5"><span>￥
                                    <?=$good->shop_price*$amount[$good->id]?></span></td>
                            <?php $total[$good->id]=$good->shop_price*$amount[$good->id]?>
                            <?php $price+=$good->shop_price*$amount[$good->id]?>
                        </tr>
                    <?php endforeach;?>

                    </tbody>
                    <tfoot>
                    <tr>
                        <td colspan="5">
                            <ul>
                                <li>
                                    <span><?=$count?>，总商品金额：</span>
                                    ￥<em id="xgx5"><?=$price?></em>
                                </li>
                                <li>
                                    应付邮费： <span id="xgx7"></span>
                                </li>

                                <li>
                                    <span>应付总额：</span>
                                    <em>￥<?=$price?></em>
                                </li>
                            </ul>
                        </td>
                    </tr>
                    </tfoot>
                </table>
            </div>
            <!-- 商品清单 end -->

        </div>

        <div class="fillin_ft">
            <input type="hidden" name="total" value="<?=serialize($total)?>"/>
            <input type="hidden" name="zje" value="<?=$price?>"/>
            <input type="submit" value="提交订单" />
            <p>应付总额：￥<strong id="xgx6"><?=$price?></strong>元</p>
    </form>
</div>
</div>

<!-- 主体部分 end -->

<div style="clear:both;"></div>
<!-- 底部版权 start -->
<div class="footer w1210 bc mt15">
    <p class="links">
        <a href="">关于我们</a> |
        <a href="">联系我们</a> |
        <a href="">人才招聘</a> |
        <a href="">商家入驻</a> |
        <a href="">千寻网</a> |
        <a href="">奢侈品网</a> |
        <a href="">广告服务</a> |
        <a href="">移动终端</a> |
        <a href="">友情链接</a> |
        <a href="">销售联盟</a> |
        <a href="">京西论坛</a>
    </p>
    <p class="copyright">
        © 2005-2013 京东网上商城 版权所有，并保留所有权利。  ICP备案证书号:京ICP证070359号
    </p>
    <p class="auth">
        <a href=""><img src="<?=Yii::getAlias('@web')?>/images/xin.png" alt="" /></a>
        <a href=""><img src="<?=Yii::getAlias('@web')?>/images/kexin.jpg" alt="" /></a>
        <a href=""><img src="<?=Yii::getAlias('@web')?>/images/police.jpg" alt="" /></a>
        <a href=""><img src="<?=Yii::getAlias('@web')?>/images/beian.gif" alt="" /></a>
    </p>
</div>
<!-- 底部版权 end -->
<script type="text/javascript">
    $('#xgx').click(function () {
        var a=$('#xgx').find('input:checked').val();
        var b=$('#xgx').find('input:checked').closest('td').next('td').find('em').text();
        $('#xgx7').text(b);
        var c=$('#xgx5').text();
        var d=parseFloat(c)+parseFloat(b);
        var x=Math.floor(d*100)/100;
        console.debug(x);
        $('#xgx6').text(x);
    })
</script>
</body>
</html>
