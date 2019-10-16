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

        $this->createTable('{{%console_jobs}}', [
            'id' => $this->primaryKey(),
            'delay' => $this->bigInteger(),
            'priority' => $this->string(),
            'class' => $this->string()->notNull(),
            'elementType' => $this->string(),
            'elementId' => $this->bigInteger(),
            'siteId' => $this->bigInteger(),
            'description' => $this->string(),
            'progress' => $this->boolean(),
            'progressLabel' => $this->string(),
            'event' => $this->json(),
            'payload' => $this->json(),
            'dateCreated' => $this->dateTime()->notNull(),
            'dateUpdated' => $this->dateTime()->notNull(),
            'uid' => $this->uid(),
        ]);

        return true;
    }

    public function safeDown()
    {
        $this->dropTable('{{%console_tokens}}');
        $this->dropTable('{{%console_jobs}}');

        return true;
    }
}
