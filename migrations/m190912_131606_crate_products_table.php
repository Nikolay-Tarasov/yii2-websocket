<?php

use yii\db\Migration;

/**
 * Class m190912_131606_crate_products_table
 */
class m190912_131606_crate_products_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('products', [
            'id' => $this->primaryKey(),
            'name' => $this->string(255)->Null(),
            'price' => $this->integer(11)->Null(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('products');

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190912_131606_crate_products_table cannot be reverted.\n";

        return false;
    }
    */
}
