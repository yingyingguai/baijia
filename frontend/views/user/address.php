<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
    <title>收货地址</title>
    <link rel="stylesheet" href="/style/base.css" type="text/css">
    <link rel="stylesheet" href="/style/global.css" type="text/css">
    <link rel="stylesheet" href="/style/header.css" type="text/css">
    <link rel="stylesheet" href="/style/home.css" type="text/css">
    <link rel="stylesheet" href="/style/address.css" type="text/css">
    <link rel="stylesheet" href="/style/bottomnav.css" type="text/css">
    <link rel="stylesheet" href="/style/footer.css" type="text/css">
    <script type="text/javascript" src="/js/jquery-1.8.3.min.js"></script>
    <script type="text/javascript" src="/js/header.js"></script>
    <script type="text/javascript" src="/js/home.js"></script>
    <script type="text/javascript" src="/js/address.js"></script>
</head>

<!-- 页面主体 start -->
<div class="main w1210 bc mt10">
    <div class="crumb w1210">
        <h2><strong>我的XX </strong><span>> 我的订单</span></h2>
    </div>

    <!-- 左侧导航菜单 start -->
    <div class="menu fl">
        <h3>我的XX</h3>
        <div class="menu_wrap">
            <dl>
                <dt>订单中心 <b></b></dt>
                <dd><b>.</b><a href="">我的订单</a></dd>
                <dd><b>.</b><a href="">我的关注</a></dd>
                <dd><b>.</b><a href="">浏览历史</a></dd>
                <dd><b>.</b><a href="">我的团购</a></dd>
            </dl>

            <dl>
                <dt>账户中心 <b></b></dt>
                <dd class="cur"><b>.</b><a href="">账户信息</a></dd>
                <dd><b>.</b><a href="">账户余额</a></dd>
                <dd><b>.</b><a href="">消费记录</a></dd>
                <dd><b>.</b><a href="">我的积分</a></dd>
                <dd><b>.</b><a href="">收货地址</a></dd>
            </dl>

            <dl>
                <dt>订单中心 <b></b></dt>
                <dd><b>.</b><a href="">返修/退换货</a></dd>
                <dd><b>.</b><a href="">取消订单记录</a></dd>
                <dd><b>.</b><a href="">我的投诉</a></dd>
            </dl>
        </div>
    </div>
    <!-- 左侧导航菜单 end -->

    <!-- 右侧内容区域 start -->
    <div class="content fl ml10">
        <div class="address_hd">
            <h3>收货地址薄</h3>
            <dl>
                <?php foreach (\frontend\models\Address::find()->where(['member_id'=>Yii::$app->user->id])->all() as $model):?>
                    <dt><?=$model->name?> <?=$model->province?> <?=$model->city?> <?=$model->area?> <?=$model->address?>  <?=$model->tel?></dt>
                    <dd>
                        <a href="/user/address-edit?id=<?=$model->id?>">修改</a>
                        <a href="/user/address-del?id=<?=$model->id?>">删除</a>
                        <a href="<?=\yii\helpers\Url::to(['user/addr-default','id'=>$model->id])?>"><?=$model->status==1?'':'设为默认地址'?></a>

                    </dd>
                <?php endforeach;?>
            </dl>

        </div>
        <div class="address_bd mt10">
            <h4>新增收货地址</h4>
            <form action="" name="AddressForm" method="post">
                <ul>
                    <li>
                        <label for=""><span>*</span>收 货 人：</label>
                        <input type="text" name="name" class="txt" />
                    </li>
                    <li>
                        <label for=""><span>*</span>所在地区：</label>
                        <select id="cmbProvince" name="province"></select>
                        <select id="cmbCity" name="city"></select>
                        <select id="cmbArea" name="area"></select>
                    </li>
                    <li>
                        <label for=""><span>*</span>详细地址：</label>
                        <input type="text" name="address" class="txt address"  />
                    </li>
                    <li>
                        <label for=""><span>*</span>手机号码：</label>
                        <input type="text" name="tel" class="txt" />
                    </li>
                    <li>
                        <label for="">&nbsp;</label>
                        <input type="checkbox" name="status" value="1" class="check" />设为默认地址
                    </li>
                    <li>
                        <label for="">&nbsp;</label>
                        <input type="submit" name="" class="btn" value="保存" />
                    </li>
                </ul>
            </form>
        </div>

    </div>
    <!-- 右侧内容区域 end -->
</div>
<!-- 页面主体 end-->

<div style="clear:both;"></div>

<!-- 底部导航 start -->
<div class="bottomnav w1210 bc mt10">
    <div class="bnav1">
        <h3><b></b> <em>购物指南</em></h3>
        <ul>
            <li><a href="">购物流程</a></li>
            <li><a href="">会员介绍</a></li>
            <li><a href="">团购/机票/充值/点卡</a></li>
            <li><a href="">常见问题</a></li>
            <li><a href="">大家电</a></li>
            <li><a href="">联系客服</a></li>
        </ul>
    </div>

    <div class="bnav2">
        <h3><b></b> <em>配送方式</em></h3>
        <ul>
            <li><a href="">上门自提</a></li>
            <li><a href="">快速运输</a></li>
            <li><a href="">特快专递（EMS）</a></li>
            <li><a href="">如何送礼</a></li>
            <li><a href="">海外购物</a></li>
        </ul>
    </div>


    <div class="bnav3">
        <h3><b></b> <em>支付方式</em></h3>
        <ul>
            <li><a href="">货到付款</a></li>
            <li><a href="">在线支付</a></li>
            <li><a href="">分期付款</a></li>
            <li><a href="">邮局汇款</a></li>
            <li><a href="">公司转账</a></li>
        </ul>
    </div>

    <div class="bnav4">
        <h3><b></b> <em>售后服务</em></h3>
        <ul>
            <li><a href="">退换货政策</a></li>
            <li><a href="">退换货流程</a></li>
            <li><a href="">价格保护</a></li>
            <li><a href="">退款说明</a></li>
            <li><a href="">返修/退换货</a></li>
            <li><a href="">退款申请</a></li>
        </ul>
    </div>

    <div class="bnav5">
        <h3><b></b> <em>特色服务</em></h3>
        <ul>
            <li><a href="">夺宝岛</a></li>
            <li><a href="">DIY装机</a></li>
            <li><a href="">延保服务</a></li>
            <li><a href="">家电下乡</a></li>
            <li><a href="">京东礼品卡</a></li>
            <li><a href="">能效补贴</a></li>
        </ul>
    </div>
</div>
<!-- 底部导航 end -->

<div style="clear:both;"></div>

<!-- 底部版权 end -->
<!--  js三级联动start-->
<script type="text/javascript">
    addressInit('cmbProvince', 'cmbCity', 'cmbArea');

    /*
    *       ajax删除
    * */

 /*   $('#delete').click(function () {
        alert(1)
    })*/

    $('#address_hd').on('click','#delete',function () {
        var id = $(this).closest('dl').attr('id');
        var dl = $(this).closest('dl');
        alert(1)
        if(confirm('是否删除该收货地址')){
            $.getJSON('<?=\yii\helpers\Url::to(['user/del-address'])?>?id='+id,function (data) {
                if(data){
                    dl.remove();
                    alert('删除成功');
                }else{
                    alert('删除失败');
                }
            })
        }
    });


</script>
<!--  js三级联动end-->


</body>

</html>