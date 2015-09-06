<?php

use yii\db\Schema;
use yii\db\Migration;

class m150905_075103_initial extends Migration
{
    public function up()
    {
        $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';

        $this->createTable("{{%users}}", [
            'id' => $this->primaryKey(),
            'email' => $this->string()->notNull(),
            'auth_key' => $this->string(32)->notNull(),
            'password_hash' => $this->string()->notNull(),
            'role' => 'ENUM("admin", "user") NOT NULL',
            'deleted' => $this->boolean()->notNull()->defaultValue(0),
            'created_at' => $this->dateTime(),
        ], $tableOptions);

        $this->createTable("{{%products}}", [
            'id' => $this->primaryKey(),
            'title' => $this->string()->notNull(),
            'count' => $this->integer()->notNull()->defaultValue(0),
            'price' => $this->decimal(10, 2),
            'deleted' => $this->boolean()->notNull()->defaultValue(0),
            'created_at' => $this->dateTime(),
        ], $tableOptions);

        $this->createTable("{{%orders}}", [
            'id' => $this->primaryKey(),
            'status' => 'ENUM("new", "confirmed") NOT NULL',
            'buyer_id' => $this->integer()->notNull(),
            'deleted' => $this->boolean()->notNull()->defaultValue(0),
            'created_at' => $this->dateTime(),
        ], $tableOptions);

        $this->addForeignKey('fk_orders__users', "{{%orders}}", 'buyer_id', '{{%users}}', 'id');

        $this->createTable("{{%order_items}}", [
            'order_id' => $this->integer()->notNull(),
            'product_id' => $this->integer()->notNull(),
            'count' => $this->integer()->notNull()->defaultValue(0),
            'created_at' => $this->dateTime(),
        ], $tableOptions);

        $this->addPrimaryKey("pk_order_items", "{{%order_items}}", ['order_id', 'product_id']);

        $this->addForeignKey('fk_order_items__orders', '{{%order_items}}', 'order_id', '{{%orders}}', 'id');
        $this->addForeignKey('fk_order_items__products', '{{%order_items}}', 'product_id', '{{%products}}', 'id');
    }

    public function down()
    {
        echo "m150905_075103_initial cannot be reverted.\n";

        return false;
    }

}
