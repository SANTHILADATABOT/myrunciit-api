<?php

require_once(dirname(__DIR__).'/IyzipayBootstrap.php');

IyzipayBootstrap::init();

class Config
{
    public static function options()
    {
        $options = new \Iyzipay\Options();
        $options->setApiKey('sandbox-nS7HSFjhMVfna74YPjbCPfh0iUT6K4bq');
        $options->setSecretKey('sandbox-fv8hhK8rljarnL47imrdwly6vza1BiUa');
        $options->setBaseUrl('https://sandbox-api.iyzipay.com');

        return $options;
    }
}