<?php
if (php_sapi_name() !== 'cli') {
    header('HTTP/1.0 403 Forbidden');
    exit('Forbidden');
}

require 'include/loader.php';

/*
 * Check exisiting database
 */
$tlds = array();
if (is_readable(TLDS_JSON)) {
    $tlds = json_decode(file_get_contents(TLDS_JSON), true);
}

if (!is_array($tlds)) {
    $tlds = array();
}


$ch = curl_init();

curl_setopt_array($ch, array(
    CURLOPT_URL             => 'http://www.miitbeian.gov.cn/basecode/query/queryDomain.action',
    CURLOPT_REFERER         => 'http://www.miitbeian.gov.cn/basecode/query/queryDomain.action',
    CURLOPT_USERAGENT       => 'Chrome/49.0.2612.0 Safari/537.36 加速乐/傻逼',
    CURLOPT_HEADER          => false,
    CURLOPT_POST            => true,
    CURLOPT_RETURNTRANSFER  => true,
    CURLOPT_POSTFIELDS      => http_build_query(array(
      'domainName'          => '',
      'domainBlur'          => '0',
      'page.pageSize'       => '1000',
      'pageNo'              => '1',
      'jumpPageNo'          => ''
    ))
));

$result = curl_exec($ch);
$result = iconv('GBK', 'UTF-8//IGNORE', $result);


$match = preg_match_all('/\<td align\=\"center\"\ class\=\"by2\">(\..*?)&nbsp;\<\/td\>/', $result, $matches);
if ($match) {
    $tlds = array_merge($tlds, $matches[1]);
}

$tlds = array_unique($tlds);
usort($tlds, 'sort_tld');

file_put_contents(TLDS_JSON, json_encode($tlds));
