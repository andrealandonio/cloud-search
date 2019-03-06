<?php

namespace WP_Cloud_Search\Aws\Common\Command;

use WP_Cloud_Search\Guzzle\Service\Description\Operation;
use WP_Cloud_Search\Guzzle\Service\Command\CommandInterface;
use WP_Cloud_Search\Guzzle\Http\Message\Response;
use WP_Cloud_Search\Guzzle\Service\Description\Parameter;
use WP_Cloud_Search\Guzzle\Service\Command\LocationVisitor\Response\XmlVisitor;
/**
 * Class used for custom AWS XML response parsing of query services
 */
class XmlResponseLocationVisitor extends \WP_Cloud_Search\Guzzle\Service\Command\LocationVisitor\Response\XmlVisitor
{
    /**
     * {@inheritdoc}
     */
    public function before(\WP_Cloud_Search\Guzzle\Service\Command\CommandInterface $command, array &$result)
    {
        parent::before($command, $result);
        // Unwrapped wrapped responses
        $operation = $command->getOperation();
        if ($operation->getServiceDescription()->getData('resultWrapped')) {
            $wrappingNode = $operation->getName() . 'Result';
            if (isset($result[$wrappingNode])) {
                $result = $result[$wrappingNode] + $result;
                unset($result[$wrappingNode]);
            }
        }
    }
    /**
     * Accounts for wrapper nodes
     * {@inheritdoc}
     */
    public function visit(\WP_Cloud_Search\Guzzle\Service\Command\CommandInterface $command, \WP_Cloud_Search\Guzzle\Http\Message\Response $response, \WP_Cloud_Search\Guzzle\Service\Description\Parameter $param, &$value, $context = null)
    {
        parent::visit($command, $response, $param, $value, $context);
        // Account for wrapper nodes (e.g. RDS, ElastiCache, etc)
        if ($param->getData('wrapper')) {
            $wireName = $param->getWireName();
            $value += $value[$wireName];
            unset($value[$wireName]);
        }
    }
    /**
     * Filter used when converting XML maps into associative arrays in service descriptions
     *
     * @param array  $value     Value to filter
     * @param string $entryName Name of each entry
     * @param string $keyName   Name of each key
     * @param string $valueName Name of each value
     *
     * @return array Returns the map of the XML data
     */
    public static function xmlMap($value, $entryName, $keyName, $valueName)
    {
        $result = array();
        foreach ($value as $entry) {
            $result[$entry[$keyName]] = $entry[$valueName];
        }
        return $result;
    }
}
