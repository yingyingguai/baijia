<?php

use yii\db\Migration;

/**
 * Handles the creation of table `address`.
 */
class m180103_023239_create_address_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('address', [
            'id' => $this->primaryKey(),
            'name'=>$this->string(50)->comment('收货人'),
            'province'=>$this->string(50)->comment('省'),
            'city'=>$this->string(50)->comment('市'),
            'area'=>$this->string(50)->comment('县'),
            'address'=>$this->string(100)->comment('详细地址'),
            'tel'=>$this->integer(12)->comment('联系电话'),
            'status'=>$this->integer(1)->comment('默认收货地址 状态 0否  1 默认'),
            'member_id'=>$this->integer(1)->comment('用户与地址关联'),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('address');
    }
}
