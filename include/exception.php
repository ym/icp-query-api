<?php

class InvalidRequestException extends Exception
{
    public function __construct($message = '')
    {
        parent::__construct($message, 400);
    }
}

class MissingDomainArgumentException extends InvalidRequestException
{
    public function __construct()
    {
        parent::__construct('域名不能为空');
    }
}

class InvalidDomainSuffixException extends InvalidRequestException
{
    public function __construct()
    {
        parent::__construct('域名后缀不在管局允许的列表中');
    }
}

class MIITSOAPException extends Exception
{
    public function __construct($message = '')
    {
        parent::__construct("管局接口返回错误：{$message}", 500);
    }
}

class UnknownException extends Exception
{
    public function __construct($message = '未知错误')
    {
        parent::__construct($message, 500);
    }
}

class InvalidXMLPayloadException extends Exception
{
    public function __construct($message = '无法解析返回的 XML 载荷')
    {
        parent::__construct($message, 500);
    }
}
