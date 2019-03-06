<?php

namespace WP_Cloud_Search\Guzzle\Plugin\Cookie\CookieJar;

use WP_Cloud_Search\Guzzle\Common\Exception\RuntimeException;
/**
 * Persists non-session cookies using a JSON formatted file
 */
class FileCookieJar extends \WP_Cloud_Search\Guzzle\Plugin\Cookie\CookieJar\ArrayCookieJar
{
    /** @var string filename */
    protected $filename;
    /**
     * Create a new FileCookieJar object
     *
     * @param string $cookieFile File to store the cookie data
     *
     * @throws RuntimeException if the file cannot be found or created
     */
    public function __construct($cookieFile)
    {
        $this->filename = $cookieFile;
        $this->load();
    }
    /**
     * Saves the file when shutting down
     */
    public function __destruct()
    {
        $this->persist();
    }
    /**
     * Save the contents of the data array to the file
     *
     * @throws RuntimeException if the file cannot be found or created
     */
    protected function persist()
    {
        if (\false === \file_put_contents($this->filename, $this->serialize())) {
            // @codeCoverageIgnoreStart
            throw new \WP_Cloud_Search\Guzzle\Common\Exception\RuntimeException('Unable to open file ' . $this->filename);
            // @codeCoverageIgnoreEnd
        }
    }
    /**
     * Load the contents of the json formatted file into the data array and discard any unsaved state
     */
    protected function load()
    {
        $json = \file_get_contents($this->filename);
        if (\false === $json) {
            // @codeCoverageIgnoreStart
            throw new \WP_Cloud_Search\Guzzle\Common\Exception\RuntimeException('Unable to open file ' . $this->filename);
            // @codeCoverageIgnoreEnd
        }
        $this->unserialize($json);
        $this->cookies = $this->cookies ?: array();
    }
}
