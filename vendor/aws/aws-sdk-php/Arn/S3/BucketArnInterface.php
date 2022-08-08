<?php

namespace WP_Cloud_Search\Aws\Arn\S3;

use WP_Cloud_Search\Aws\Arn\ArnInterface;
/**
 * @internal
 */
interface BucketArnInterface extends ArnInterface
{
    public function getBucketName();
}
