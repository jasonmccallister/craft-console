<?php

namespace mccallister\console\models;

use craft\base\Model;
use craft\helpers\Json;
use craft\validators\UniqueValidator;

class Token extends Model
{
    // Properties
    // =========================================================================

    /**
     * @var int|null ID
     */
    public $id;

    /**
     * @var string Schema name
     */
    public $name;

    /**
     * @var string The access token
     */
    public $accessToken;

    /**
     * @var bool Is the schema enabled
     */
    public $enabled = true;

    /**
     * @var \DateTime|null Date expires
     */
    public $expiryDate;

    /**
     * @var \DateTime|null Date last used
     */
    public $lastUsed;

    /**
     * @var array The schema’s scope
     */
    public $scope = [];

    /**
     * @var \DateTime|null Date created
     */
    public $dateCreated;

    /**
     * @var string $uid
     */
    public $uid;

    // Public Methods
    // =========================================================================

    public function __construct($config = [])
    {
        parent::__construct($config);

        if (is_string($this->scope)) {
            $this->scope = Json::decodeIfJson($this->scope);
        }
    }

    /**
     * @inheritdoc
     */
    public function datetimeAttributes(): array
    {
        $attributes = parent::datetimeAttributes();
        $attributes[] = 'expiryDate';
        $attributes[] = 'lastUsed';
        return $attributes;
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        $rules = parent::rules();
        $rules[] = [['name', 'accessToken'], 'required'];
        $rules[] = [
            ['name', 'accessToken'],
            UniqueValidator::class,
            'targetClass' => GqlSchemaRecord::class,
        ];

        return $rules;
    }

    /**
     * Use the translated group name as the string representation.
     *
     * @return string
     */
    public function __toString(): string
    {
        return $this->name;
    }

    /**
     * returns whether this is the public schema.
     *
     * @return bool
     */
    public function getIsPublic(): bool
    {
        return $this->accessToken === self::PUBLIC_TOKEN;
    }

    /**
     * Return whether this schema can perform an action
     *
     * @param $name
     * @return bool
     */
    public function has(string $name): bool
    {
        return is_array($this->scope) && in_array($name, $this->scope, true);
    }
}
