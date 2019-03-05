<?php

namespace WP_Cloud_Search\Guzzle\Http\QueryAggregator;

use WP_Cloud_Search\Guzzle\Http\QueryString;
/**
 * Does not aggregate nested query string values and allows duplicates in the resulting array
 *
 * Example: http://test.com?q=1&q=2
 */
class DuplicateAggregator implements \WP_Cloud_Search\Guzzle\Http\QueryAggregator\QueryAggregatorInterface
{
    public function aggregate($key, $value, \WP_Cloud_Search\Guzzle\Http\QueryString $query)
    {
        if ($query->isUrlEncoding()) {
            return array($query->encodeValue($key) => \array_map(array($query, 'encodeValue'), $value));
        } else {
            return array($key => $value);
        }
    }
}
