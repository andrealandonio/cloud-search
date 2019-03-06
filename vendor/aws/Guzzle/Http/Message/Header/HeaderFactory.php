<?php

namespace WP_Cloud_Search\Guzzle\Http\Message\Header;

use WP_Cloud_Search\Guzzle\Http\Message\Header;
/**
 * Default header factory implementation
 */
class HeaderFactory implements \WP_Cloud_Search\Guzzle\Http\Message\Header\HeaderFactoryInterface
{
    /** @var array */
    protected $mapping = array('cache-control' => 'WP_Cloud_Search\\Guzzle\\Http\\Message\\Header\\CacheControl', 'link' => 'WP_Cloud_Search\\Guzzle\\Http\\Message\\Header\\Link');
    public function createHeader($header, $value = null)
    {
        $lowercase = \strtolower($header);
        return isset($this->mapping[$lowercase]) ? new $this->mapping[$lowercase]($header, $value) : new \WP_Cloud_Search\Guzzle\Http\Message\Header($header, $value);
    }
}
