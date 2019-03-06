<?php

namespace WP_Cloud_Search\Guzzle\Http\QueryAggregator;

use WP_Cloud_Search\Guzzle\Http\QueryString;
/**
 * Aggregates nested query string variables using commas
 */
class CommaAggregator implements \WP_Cloud_Search\Guzzle\Http\QueryAggregator\QueryAggregatorInterface
{
    public function aggregate($key, $value, \WP_Cloud_Search\Guzzle\Http\QueryString $query)
    {
        if ($query->isUrlEncoding()) {
            return array($query->encodeValue($key) => \implode(',', \array_map(array($query, 'encodeValue'), $value)));
        } else {
            return array($key => \implode(',', $value));
        }
    }
}
