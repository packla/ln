<?php

use app\entities\DomainsAr;
use yii\db\Migration;

/**
 * Class m171209_101847_create_domains
 */
class m171209_101847_create_domains extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->createTable(DomainsAr::tableName(), [
            'id'     => $this->primaryKey(),
            'domain' => $this->string()->notNull(),
            'data'   => 'JSON NOT NULL',
        ], 'DEFAULT CHARACTER SET=utf8 DEFAULT COLLATE=utf8_general_ci ENGINE=InnoDB');
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        return $this->dropTable(DomainsAr::tableName());
    }
}
