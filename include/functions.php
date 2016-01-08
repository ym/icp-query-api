<?php

if (!function_exists('random_int')) {
  function random_int($min, $max) {
    return mt_rand($min, $max);
  }
}

function randstr($length = 20, $chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ') {
  $len = strlen($chars);
  $ret = '';

  for ($i = 0; $i < $length; $i++) {
      $ret .= $chars[random_int(0, $len - 1)];
  }
  return $ret;
}

function response($data, $code = 200) {
  header('Content-Type: application/json; charset=UTF8');
  $opts = 0;
  echo json_encode($data);
  exit;
}

function sort_tld($a, $b) {
  $a = substr_count($a, '.');
  $b = substr_count($b, '.');

  if ($a == $b) return 0;
  return ($a > $b) ? -1 : 1;
}

function extract_domain($domain) {
  static $tlds = array();
  if (
    empty($tlds) &&
    is_readable(TLDS_JSON)
  ) {
    $tlds = json_decode(file_get_contents(TLDS_JSON), true);
    if (!is_array($tlds)) {
      $tlds = array();
    }
  }

  $domain = trim($domain);

  foreach($tlds as $tld) {
    if (
      ($pos = strrpos($domain, $tld)) === false
    ) continue;

    if (
      strlen($domain) - $pos != strlen($tld)
    ) continue;

    $domain = substr($domain, 0, $pos);
    $domain = substr($domain, strrpos($domain, '.')) . $tld;

    $domain = trim($domain, '.');

    return $domain;
  }

  return false;
}

function exception_handler($ex) {
  response(array(
    'success' => false,
    'message' => $ex->getMessage()
  ), $ex->getCode());
}
