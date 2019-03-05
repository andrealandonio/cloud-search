<?php

namespace WP_Cloud_Search\Guzzle\Service\Command\LocationVisitor\Request;

use WP_Cloud_Search\Guzzle\Service\Command\CommandInterface;
use WP_Cloud_Search\Guzzle\Http\Message\RequestInterface;
use WP_Cloud_Search\Guzzle\Service\Description\Parameter;
abstract class AbstractRequestVisitor implements \WP_Cloud_Search\Guzzle\Service\Command\LocationVisitor\Request\RequestVisitorInterface
{
    /**
     * @codeCoverageIgnore
     */
    public function after(\WP_Cloud_Search\Guzzle\Service\Command\CommandInterface $command, \WP_Cloud_Search\Guzzle\Http\Message\RequestInterface $request)
    {
    }
    /**
     * @codeCoverageIgnore
     */
    public function visit(\WP_Cloud_Search\Guzzle\Service\Command\CommandInterface $command, \WP_Cloud_Search\Guzzle\Http\Message\RequestInterface $request, \WP_Cloud_Search\Guzzle\Service\Description\Parameter $param, $value)
    {
    }
    /**
     * Prepare (filter and set desired name for request item) the value for request.
     *
     * @param mixed                                     $value
     * @param \Guzzle\Service\Description\Parameter     $param
     *
     * @return array|mixed
     */
    protected function prepareValue($value, \WP_Cloud_Search\Guzzle\Service\Description\Parameter $param)
    {
        return \is_array($value) ? $this->resolveRecursively($value, $param) : $param->filter($value);
    }
    /**
     * Map nested parameters into the location_key based parameters
     *
     * @param array     $value Value to map
     * @param Parameter $param Parameter that holds information about the current key
     *
     * @return array Returns the mapped array
     */
    protected function resolveRecursively(array $value, \WP_Cloud_Search\Guzzle\Service\Description\Parameter $param)
    {
        foreach ($value as $name => &$v) {
            switch ($param->getType()) {
                case 'object':
                    if ($subParam = $param->getProperty($name)) {
                        $key = $subParam->getWireName();
                        $value[$key] = $this->prepareValue($v, $subParam);
                        if ($name != $key) {
                            unset($value[$name]);
                        }
                    } elseif ($param->getAdditionalProperties() instanceof \WP_Cloud_Search\Guzzle\Service\Description\Parameter) {
                        $v = $this->prepareValue($v, $param->getAdditionalProperties());
                    }
                    break;
                case 'array':
                    if ($items = $param->getItems()) {
                        $v = $this->prepareValue($v, $items);
                    }
                    break;
            }
        }
        return $param->filter($value);
    }
}
