<?php

namespace WP_Cloud_Search\Aws\Arn\S3;

use WP_Cloud_Search\Aws\Arn\ArnInterface;
/**
 * @internal
 */
interface OutpostsArnInterface extends ArnInterface
{
    public function getOutpostId();
}
