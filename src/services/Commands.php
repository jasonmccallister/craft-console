<?php

namespace mccallister\console\services;

use craft\base\Component;

class Commands extends Component
{
    /**
     * Gets all of the commands that console allows to run.
     *
     * @return array
     */
    public function all(): array
    {
        return [
            'backup/db' => 'Creates a new database backup',
            'cache/flush-all' => 'Flushes all caches registered in the system.',
            // 'cache/flush-schema',
            // 'cache/index',
            // 'clear-caches/all',
            // 'clear-caches/asset',
            // 'clear-caches/asset-indexing-data',
            // 'clear-caches/compiled-templates',
            // 'clear-caches/cp-resources',
            // 'clear-caches/data',
            // 'clear-caches/index',
            // 'clear-caches/temp-files',
            // 'clear-caches/template-caches',
            // 'clear-caches/transform-indexes',
            // 'gc/run' => 'theclass',
            // //
            // 'graphql/dump-schema',
            // 'graphql/print-schema',
            // // resave commands
            // 'resave/assets',
            // 'resave/categories',
            // 'resave/entries' => 'theclass',
            // 'resave/matrix-blocks',
            // 'resave/tags',
            // 'resave/users',
        ];
    }
}
