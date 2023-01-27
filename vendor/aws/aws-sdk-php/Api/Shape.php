<?php

namespace WP_Cloud_Search\Aws\Api;

/**
 * Base class representing a modeled shape.
 */
class Shape extends AbstractModel
{
    /**
     * Get a concrete shape for the given definition.
     *
     * @param array    $definition
     * @param ShapeMap $shapeMap
     *
     * @return mixed
     * @throws \RuntimeException if the type is invalid
     */
    public static function create(array $definition, ShapeMap $shapeMap)
    {
        static $map = ['structure' => 'WP_Cloud_Search\\Aws\\Api\\StructureShape', 'map' => 'WP_Cloud_Search\\Aws\\Api\\MapShape', 'list' => 'WP_Cloud_Search\\Aws\\Api\\ListShape', 'timestamp' => 'WP_Cloud_Search\\Aws\\Api\\TimestampShape', 'integer' => 'WP_Cloud_Search\\Aws\\Api\\Shape', 'double' => 'WP_Cloud_Search\\Aws\\Api\\Shape', 'float' => 'WP_Cloud_Search\\Aws\\Api\\Shape', 'long' => 'WP_Cloud_Search\\Aws\\Api\\Shape', 'string' => 'WP_Cloud_Search\\Aws\\Api\\Shape', 'byte' => 'WP_Cloud_Search\\Aws\\Api\\Shape', 'character' => 'WP_Cloud_Search\\Aws\\Api\\Shape', 'blob' => 'WP_Cloud_Search\\Aws\\Api\\Shape', 'boolean' => 'WP_Cloud_Search\\Aws\\Api\\Shape'];
        if (isset($definition['shape'])) {
            return $shapeMap->resolve($definition);
        }
        if (!isset($map[$definition['type']])) {
            throw new \RuntimeException('Invalid type: ' . \print_r($definition, \true));
        }
        $type = $map[$definition['type']];
        return new $type($definition, $shapeMap);
    }
    /**
     * Get the type of the shape
     *
     * @return string
     */
    public function getType()
    {
        return $this->definition['type'];
    }
    /**
     * Get the name of the shape
     *
     * @return string
     */
    public function getName()
    {
        return $this->definition['name'];
    }
}
