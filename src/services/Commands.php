<?php

namespace mccallister\console\services;

use craft\base\Component;
use InvalidArgumentException;

class Commands extends Component
{
    /**
     * The commands that are available for the console plugin.
     *
     * @var array
     */
    protected $commands = [
        'backup/db' => [
            'description' => 'Creates a new database backup.',
            'namespace' => 'craft\console\controllers',
        ],
        'cache/flush-all' => [
            'description' => 'Flushes all caches registered in the system.',
            'namespace' => '',
        ],
    ];

    /**
     * Gets all of the commands that console allows to run.
     *
     * @return array
     */
    public function all(): array
    {
       return $this->commands;
    }

    /**
     * Get a command by its name.
     *
     * @throws InvalidArgumentException
     * @return array
     */
    public function get(string $name): array
    {
        if (!array_key_exists($name, $this->commands)) {
            throw new InvalidArgumentException('The command with the name ' . $name . ' is not allowed.');
        }

       return $this->commands[$name];
    }
}
