<?php

namespace mccallister\console\migrations;

use craft\db\Migration;

/**
 * @author    Jason McCallister
 * @package   Console
 * @since     1.0.0
 */
class Install extends Migration
{
    // Public Methods
    // =========================================================================

    public function safeUp()
    {
        $this->createTable('{{%console_tokens}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull(),
            'accessToken' => $this->string()->notNull(),
            'enabled' => $this->boolean()->notNull()->defaultValue(true),
            'expiryDate' => $this->dateTime(),
            'lastUsed' => $this->dateTime(),
            'scope' => $this->text(),
            'dateCreated' => $this->dateTime()->notNull(),
            'dateUpdated' => $this->dateTime()->notNull(),
            'uid' => $this->uid(),
        ]);

        return true;
    }

    public function safeDown()
    {
        $this->dropTable('{{%console_tokens}}');

        return true;
    }
}
