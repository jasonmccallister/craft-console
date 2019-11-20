<?php

namespace mccallister\console\contracts;

interface TransformsElements
{
    /**
     * Transforms a specific resource to an array.
     *
     * @param mixed $resource
     * @param mixed $transformer
     *
     * @return array
     */
    public function transformItem($resource, $transformer): array;

    /**
     * Transforms a collection to resources to an array.
     *
     * @param mixed $resources
     * @param mixed $transformer
     *
     * @return array
     */
    public function transformCollection($resources, $transformer): array;
}
