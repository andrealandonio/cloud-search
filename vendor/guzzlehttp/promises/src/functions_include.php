<?php

namespace WP_Cloud_Search;

// Don't redefine the functions if included multiple times.
if (!\function_exists('WP_Cloud_Search\\GuzzleHttp\\Promise\\promise_for')) {
    require __DIR__ . '/functions.php';
}
