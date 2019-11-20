<?php

namespace mccallister\console\records;

use craft\db\ActiveRecord;

class AssetEvent extends ActiveRecord
{
    // Public Static Methods
    // =========================================================================

    /**
     * @inheritdoc
     *
     * @return string the table name
     */
    public static function tableName(): string
    {
        return '{{%console_asset_events}}';
    }
}
