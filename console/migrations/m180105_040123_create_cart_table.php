<?php

use yii\db\Migration;

/**
 * Handles the creation of table `cart`.
 */
class m180105_040123_create_cart_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('cart', [
            'id' => $this->primaryKey(),
            'goods_id'=>$this->integer(10)->comment('商品id'),
            'amount'=>$this->integer(10)->comment('购买数量'),
            'member_id'=>$this->integer(10)->comment('用户id'),
        ],'ENGINE=INNODB');
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('cart');
    }
}
