<?php

namespace WP_Cloud_Search\Guzzle\Service\Exception;

use WP_Cloud_Search\Guzzle\Common\Exception\RuntimeException;
/**
 * Command transfer exception when commands do not all use the same client
 */
class InconsistentClientTransferException extends \WP_Cloud_Search\Guzzle\Common\Exception\RuntimeException
{
    /**
     * @var array Commands with an invalid client
     */
    private $invalidCommands = array();
    /**
     * @param array $commands Invalid commands
     */
    public function __construct(array $commands)
    {
        $this->invalidCommands = $commands;
        parent::__construct('Encountered commands in a batch transfer that use inconsistent clients. The batching ' . 'strategy you use with a command transfer must divide command batches by client.');
    }
    /**
     * Get the invalid commands
     *
     * @return array
     */
    public function getCommands()
    {
        return $this->invalidCommands;
    }
}
