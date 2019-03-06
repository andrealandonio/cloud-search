<?php

namespace WP_Cloud_Search\Guzzle\Batch\Exception;

use WP_Cloud_Search\Guzzle\Common\Exception\GuzzleException;
use WP_Cloud_Search\Guzzle\Batch\BatchTransferInterface as TransferStrategy;
use WP_Cloud_Search\Guzzle\Batch\BatchDivisorInterface as DivisorStrategy;
/**
 * Exception thrown during a batch transfer
 */
class BatchTransferException extends \Exception implements \WP_Cloud_Search\Guzzle\Common\Exception\GuzzleException
{
    /** @var array The batch being sent when the exception occurred */
    protected $batch;
    /** @var TransferStrategy The transfer strategy in use when the exception occurred */
    protected $transferStrategy;
    /** @var DivisorStrategy The divisor strategy in use when the exception occurred */
    protected $divisorStrategy;
    /** @var array Items transferred at the point in which the exception was encountered */
    protected $transferredItems;
    /**
     * @param array            $batch            The batch being sent when the exception occurred
     * @param array            $transferredItems Items transferred at the point in which the exception was encountered
     * @param \Exception       $exception        Exception encountered
     * @param TransferStrategy $transferStrategy The transfer strategy in use when the exception occurred
     * @param DivisorStrategy  $divisorStrategy  The divisor strategy in use when the exception occurred
     */
    public function __construct(array $batch, array $transferredItems, \Exception $exception, \WP_Cloud_Search\Guzzle\Batch\BatchTransferInterface $transferStrategy = null, \WP_Cloud_Search\Guzzle\Batch\BatchDivisorInterface $divisorStrategy = null)
    {
        $this->batch = $batch;
        $this->transferredItems = $transferredItems;
        $this->transferStrategy = $transferStrategy;
        $this->divisorStrategy = $divisorStrategy;
        parent::__construct('Exception encountered while transferring batch: ' . $exception->getMessage(), $exception->getCode(), $exception);
    }
    /**
     * Get the batch that we being sent when the exception occurred
     *
     * @return array
     */
    public function getBatch()
    {
        return $this->batch;
    }
    /**
     * Get the items transferred at the point in which the exception was encountered
     *
     * @return array
     */
    public function getTransferredItems()
    {
        return $this->transferredItems;
    }
    /**
     * Get the transfer strategy
     *
     * @return TransferStrategy
     */
    public function getTransferStrategy()
    {
        return $this->transferStrategy;
    }
    /**
     * Get the divisor strategy
     *
     * @return DivisorStrategy
     */
    public function getDivisorStrategy()
    {
        return $this->divisorStrategy;
    }
}
