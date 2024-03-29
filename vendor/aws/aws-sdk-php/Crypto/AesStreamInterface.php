<?php

namespace WP_Cloud_Search\Aws\Crypto;

use WP_Cloud_Search\Psr\Http\Message\StreamInterface;
interface AesStreamInterface extends StreamInterface
{
    /**
     * Returns an identifier recognizable by `openssl_*` functions, such as
     * `aes-256-cbc` or `aes-128-ctr`.
     *
     * @return string
     */
    public function getOpenSslName();
    /**
     * Returns an AES recognizable name, such as 'AES/GCM/NoPadding'.
     *
     * @return string
     */
    public function getAesName();
    /**
     * Returns the IV that should be used to initialize the next block in
     * encrypt or decrypt.
     *
     * @return string
     */
    public function getCurrentIv();
}
