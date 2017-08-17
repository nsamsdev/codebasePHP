<?php
require __DIR__ . '/../vendor/autoload.php';
define('CODEBASE', __DIR__ . '/../');

require __DIR__ . '/../configs/configs-cli.php';
require __DIR__ . '/../helpers/helpers.php';

use CodeBase\HttpRequester as Request;

$headerNavsFile = LOCATION_FOR_HEADER_API_NAVS;
$footerNavsFile = LOCATION_FOR_FOOTER_API_NAVS;

$headerResponse = Request::callUrlWithData([
    'from' => 'just data'
], 'json', HEADER_API);
$footerResponse = Request::callUrlWithData([
    'from' => 'just data'
], 'json', FOOTER_API);

$jsonHeaderData = [];
$jsonFooterData = [];

$jsonHeader = json_decode($headerResponse->raw_body, true);
$jsonFooter = json_decode($footerResponse->raw_body, true);

foreach ($jsonHeader as $jh) {
    $jsonHeaderData[] = [
        'title' => $jh['title'],
        'url' => $jh['url']
    ];
}

foreach ($jsonFooter as $jf) {
    $jsonFooterData[] = [
        'title' => $jf['title'],
        'url' => $jf['url']
    ];
}


$headerfile = fopen($headerNavsFile, 'r+');
fwrite($headerfile, serialize($jsonHeaderData));
fclose($headerfile);

// var_dump($footerResponse->raw_body);die;
// echo $headerResponse->raw_boby;die;
// var_dump(
// [$headerNavsFile,
// $footerNavsFile]
// );die;
// file_put_contents($headerResponse->raw_body, $headerNavsFile, LOCK_EX);
// file_put_contents($footerResponse->raw_body, $footerNavsFile, LOCK_EX);
$footerfile = fopen($footerNavsFile, 'r+');
fwrite($footerfile, serialize($jsonFooterData));
fclose($footerfile);

print("\nFinished\n");
