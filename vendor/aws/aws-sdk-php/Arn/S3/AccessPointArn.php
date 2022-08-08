<?php

namespace WP_Cloud_Search\Aws\Arn\S3;

use WP_Cloud_Search\Aws\Arn\AccessPointArn as BaseAccessPointArn;
use WP_Cloud_Search\Aws\Arn\AccessPointArnInterface;
use WP_Cloud_Search\Aws\Arn\ArnInterface;
use WP_Cloud_Search\Aws\Arn\Exception\InvalidArnException;
/**
 * @internal
 */
class AccessPointArn extends BaseAccessPointArn implements AccessPointArnInterface
{
    /**
     * Validation specific to AccessPointArn
     *
     * @param array $data
     */
    public static function validate(array $data)
    {
        parent::validate($data);
        if ($data['service'] !== 's3') {
            throw new InvalidArnException("The 3rd component of an S3 access" . " point ARN represents the region and must be 's3'.");
        }
    }
}
