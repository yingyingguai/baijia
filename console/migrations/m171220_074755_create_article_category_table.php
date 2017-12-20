<?php

use yii\db\Migration;

/**
 * Handles the creation of table `article_category`.
 */
class m171220_074755_create_article_category_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('article_category', [
            'id' => $this->primaryKey(),
            'name'=>$this->string(50)->comment('姓名'),
            'intro'=>$this->string(255)->comment('简介'),
            'sort'=>$this->integer(11)->comment('排序'),
            'status'=>$this->integer(1)->comment('状态'),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('article_category');
    }
}
