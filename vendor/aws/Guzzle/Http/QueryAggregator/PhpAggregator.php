<?php

namespace WP_Cloud_Search\Guzzle\Http\QueryAggregator;

use WP_Cloud_Search\Guzzle\Http\QueryString;
/**
 * Aggregates nested query string variables using PHP style []
 */
class PhpAggregator implements \WP_Cloud_Search\Guzzle\Http\QueryAggregator\QueryAggregatorInterface
{
    public function aggregate($key, $value, \WP_Cloud_Search\Guzzle\Http\QueryString $query)
    {
        $ret = array();
        foreach ($value as $k => $v) {
            $k = "{$key}[{$k}]";
            if (\is_array($v)) {
                $ret = \array_merge($ret, self::aggregate($k, $v, $query));
            } else {
                $ret[$query->encodeValue($k)] = $query->encodeValue($v);
            }
        }
        return $ret;
    }
}
