<?php

use yii\db\Migration;

/**
 * Handles the creation of table `member`.
 */
class m180102_094325_create_member_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('member', [
            'id' => $this->primaryKey(),
            'username'=>$this->string(50)->comment('用户名'),
            'auth_key'=>$this->string()->comment(''),
            'password_hash'=>$this->string(255)->comment('密码'),
            'email'=>$this->string(100)->comment('邮箱'),
            'tel'=>$this->char(11)->comment('电话'),
            'last_login_time'=>$this->integer(50)->comment('最后登录时间'),
            'last_login_ip'=>$this->string(100)->comment('最后登录ip'),
            'status'=>$this->integer(1)->comment('状态 0删除  1 正常'),
            'create_at'=>$this->integer(50)->comment('添加时间常'),
            'update_at'=>$this->integer(50)->comment('修改时间'),

        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('member');
    }
}
