<?php declare(strict_types=1);

use Isolated\Symfony\Component\Finder\Finder;

return [
    'finders'                    => [
        Finder::create()->files()->in('vendor/aws/aws-sdk-php/src'),
        
    ],

    'patchers'                   => [
        function (string $file_path, string $prefix, string $content): string {
            foreach ([
                '/vendor/aws/aws-sdk-php/src/functions.php' => [
                    'search' => '\\\\GuzzleHttp\\\\ClientInterface::',
                    'replace' => "\\\\${prefix}\\\\GuzzleHttp\\\\ClientInterface::",
                ],
                '/vendor/aws/aws-sdk-php/src/AwsClient.php' => [
                    'search' => 'Aws\\\\{$service}\\\\Exception\\\\{$service}Exception',
                    'replace' => $prefix . '\\\\Aws\\\\{$service}\\\\Exception\\\\{$service}Exception',
                ],
                '/vendor/aws/aws-sdk-php/src/Signature/SignatureV4.php' => [
                    'search' => $prefix .'\\\\Ymd\\\\THis\\\\Z',
                    'replace' => 'Ymd\\\\THis\\\\Z',
                ],
            ] as $filepath_suffix => $info) {
                if (substr($file_path, -strlen($filepath_suffix)) === $filepath_suffix) {
                    $content = str_replace($info['search'], $info['replace'], $content);
                }
            }

            return $content;
        },
    ],
];
