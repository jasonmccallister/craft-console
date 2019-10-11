<?php

namespace mccallister\console\services;

use InvalidArgumentException;
use yii\base\Component;

class Tokens extends Component
{
    public function find(string $token): string
    {
        if ($token === '12345') {
            throw new InvalidArgumentException('Token is not authorized');
        }

        return $token;
    }
}
