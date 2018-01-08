<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "order".
 *
 * @property integer $id
 * @property integer $member_id
 * @property string $name
 * @property string $province
 * @property string $city
 * @property string $area
 * @property string $address
 * @property string $tel
 * @property string $delivery_id
 * @property string $delivery_name
 * @property double $delivery_price
 * @property integer $payment_id
 * @property string $payment_name
 * @property string $total
 * @property integer $status
 * @property string $trade_no
 * @property integer $create_time
 */
class Order extends \yii\db\ActiveRecord
{
    //送货方式
    public static $deliveries=[
        1=>['name'=>'天天快递','price'=>'28.00','detail'=>'天天牛逼'],
        2=>['name'=>'韵达快递','price'=>'25.00','detail'=>'韵达你好棒'],
        3=>['name'=>'菜鸟驿站','price'=>'18.00','detail'=>'菜鸟你最菜'],
        4=>['name'=>'中通快递','price'=>'22.00','detail'=>'我是最棒的'],
    ];

    //支付方式
    public static $payments=[
        1=>['name'=>'货到付款','content'=>'送货上门后再收款，支持现金、POS机刷卡、支票支付'],
        2=>['name'=>'在线支付','content'=>'即时到帐，支持绝大数银行借记卡及部分银行信用卡'],
        3=>['name'=>'上门自提','content'=>'自提时付款，支持现金、POS刷卡、支票支付'],
    ];
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'order';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['member_id','delivery_id', 'payment_id', 'status', 'create_time'], 'integer'],
            [['delivery_price', 'total'], 'number'],
            [['name'], 'string', 'max' => 50],
            [['province', 'city', 'area'], 'string', 'max' => 20],
            [['address', 'delivery_id', 'delivery_name', 'payment_name', 'trade_no'], 'string', 'max' => 255],
            [['tel'], 'string', 'max' => 11],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'member_id' => '用户ID',
            'name' => '收货人',
            'province' => '省',
            'city' => '市',
            'area' => '县',
            'address' => '详细地址',
            'tel' => '电话号码',
            'delivery_id' => '配送方式ID',
            'delivery_name' => '配送方式名称',
            'delivery_price' => '配送方式价格',
            'payment_id' => '支付方式ID',
            'payment_name' => '支付方式名称',
            'total' => '订单金额',
            'status' => '订单状态(0已取消 1待付款 2待发货 3待发货 4完成)',
            'trade_no' => '第三方支付交易号',
            'create_time' => '创建时间',
        ];
    }
}
