<?php
require 'include/loader.php';

$domain = '';

if (IS_CLI) {
    if ($argc < 2) {
        throw new MissingDomainArgumentException();
    }
    $domain = $argv[1];
} else {
    if (!isset($_REQUEST['domain'])) {
        throw new MissingDomainArgumentException();
    }
    $domain = $_REQUEST['domain'];
}

$domain = extract_domain($domain);
if ($domain === false) {
    throw new InvalidDomainSuffixException();
}

$client = new SoapClient(BEIAN_WSDL_URL);

$key = ISP_PASSWORD;
$rand = randstr(20);

$params = array(
    'ispID'               => ISP_ID,
    'userName'            => ISP_USERNAME,

    'randVal'             => $rand,
    'pwdHash'             => base64_encode(md5($key . $rand, true)),
    'hashAlgorithm'       => 0,

    'queryConditionType'  => 0,
    'queryCondition'      => extract_domain($domain),
);

$response = $client->__soapCall("isp_querybeianstatus", array($params));
if (!$response || !$response->return) {
    throw new UnknownException();
}

$return = str_replace('GBK', 'UTF-8', $response->return);
$return = simplexml_load_string($return);

if (!$return) {
    throw new InvalidXMLPayloadException();
}

if (intval($return->msg_code) !== 0) {
    throw new MIITSOAPException($return->msg);
}


$ret = array(
    'success' => true,
    'domain'  => $domain,
    'status'  => intval($return->StatusInfo->Bazt) == 0
);

if ($ret['status']) {
    $ret = array_merge($ret, array(
      'name'         => (string) $return->StatusInfo->Wzmc,
      'icp_subject'  => (string) $return->StatusInfo->Ztbah,
      'icp_site'     => (string) $return->StatusInfo->Wzbah
    ));
}

response($ret);
