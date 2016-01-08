<?php

define('BEIAN_WSDL_URL',  '');  // 省系统备案状态查询地址	
define('ISP_ID',          '');  // 接入服务提供者的标识，可在部或省局系统的公共查询中查询得到
define('ISP_USERNAME',    '');  // 用户名，由企业所在省管局（或部管局）维护管理
define('ISP_PASSWORD',    '');  // 用户口令，由企业所在省管局（或部管局）维护管理

define('DS',              DIRECTORY_SEPARATOR);

define('DATA_DIR',        dirname(dirname(__FILE__)) . DS . 'data');

define('CONDITIONS_JSON', DATA_DIR . DS . 'conditions.json');
define('TLDS_JSON',       DATA_DIR . DS . 'tlds.json');

define('IS_CLI',          php_sapi_name() == 'cli');
