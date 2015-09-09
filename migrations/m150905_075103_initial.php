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
            'price' => $this->decimal(10, 2)->notNull(),
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
        ], $tableOptions);

        $this->addPrimaryKey("pk_order_items", "{{%order_items}}", ['order_id', 'product_id']);

        $this->addForeignKey('fk_order_items__orders', '{{%order_items}}', 'order_id', '{{%orders}}', 'id');
        $this->addForeignKey('fk_order_items__products', '{{%order_items}}', 'product_id', '{{%products}}', 'id');


        $this->insert('{{%users}}', [
            'id' => 1,
            'email' => 'admin@admin.admin',
            'auth_key' => 'qstKbnPPkQ_MnPpLLpAFKbT2H5lwpBbV',
            'password_hash' => '$2y$13$aXrY1rBqZXbSoDpYYgSO5OJBTPOLtZ5E/HHqyqZmF.Kd9m/IcPycS',
            'role' => 'admin',
            'deleted' => '0',
            'created_at' => null,
        ]);

        $this->insert('{{%users}}', [
            'id' => 2,
            'email' => 'user@user.user',
            'auth_key' => 'VseC3aLDt5EySy8CLr0M5zdBYgW6O0XC',
            'password_hash' => '$2y$13$9ZDehgiEpl1mOowtCxOq7u5l41vBI3sFjfkZB023iuV1ZfSagfFRy',
            'role' => 'user',
            'deleted' => '0',
            'created_at' => null,
        ]);

        $this->insert('{{%products}}', [
            'id' => 1,
            'title' => 'Vertu S45',
            'count' => 40,
            'price' => 22000,
            'deleted' => '0',
            'created_at' => null,
        ]);

        $this->insert('{{%products}}', [
            'id' => 2,
            'title' => 'Gilette H67 HD',
            'count' => 12,
            'price' => 101,
            'deleted' => '0',
            'created_at' => null,
        ]);

        $this->insert('{{%products}}', [
            'id' => 3,
            'title' => 'Yamaha G6-i8 Core2Duo',
            'count' => 6,
            'price' => 526,
            'deleted' => '0',
            'created_at' => null,
        ]);

        $this->insert('{{%products}}', [
            'id' => 4,
            'title' => 'Apple T2 Antonovka',
            'count' => 12,
            'price' => 100,
            'deleted' => '0',
            'created_at' => null,
        ]);
    }

    public function down()
    {
        $this->dropTable('{{%order_items}}');
        $this->dropTable('{{%orders}}');
        $this->dropTable('{{%products}}');
        $this->dropTable('{{%users}}');
    }

}
