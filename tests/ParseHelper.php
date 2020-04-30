<?php

namespace Parziphal\Parse\Test;

use Parse\HttpClients\ParseCurlHttpClient;
use Parse\HttpClients\ParseStreamHttpClient;
use Parse\ParseClient;
use Parse\ParseObject;
use Parse\ParseQuery;

class ParseHelper
{
    public static $appId      = 'test_app';
    public static $restKey    = 'test_key';
    public static $masterKey  = 'test_key';
    public static $serverUrl  = 'http://localhost:1337/';
    public static $mountPath = 'parse';
    public static $accountKey = 'account-key';


    public static function setUp()
    {
        ini_set('error_reporting', E_ALL);
        ini_set('display_errors', 1);
        date_default_timezone_set('UTC');

        ParseClient::initialize(
            self::$appId,
            self::$restKey,
            self::$masterKey,
            true,
            self::$accountKey
        );
        self::setServerURL();
        self::setHttpClient();
    }

    public static function setHttpClient()
    {
        //
        // Set a curl http client to run primary tests under
        // may be:
        //
        // ParseCurlHttpClient
        // ParseStreamHttpClient
        //

        global $USE_CLIENT_STREAM;

        if (isset($USE_CLIENT_STREAM)) {
            // stream client
            ParseClient::setHttpClient(new ParseStreamHttpClient());
        } else {
            // default client set
            if (function_exists('curl_init')) {
                // cURL client
                ParseClient::setHttpClient(new ParseCurlHttpClient());
            } else {
                // stream client
                ParseClient::setHttpClient(new ParseStreamHttpClient());
            }
        }
    }

    public static function setServerURL()
    {
        ParseClient::setServerURL(self::$serverUrl, self::$mountPath);
    }

    public static function tearDown()
    {
    }

    public static function clearClass($class)
    {
        $query = new ParseQuery($class);
        $query->each(
            function (ParseObject $obj) {
                $obj->destroy(true);
            },
            true
        );
    }

    public static function setUpWithoutCURLExceptions()
    {
        ParseClient::initialize(
            self::$appId,
            self::$restKey,
            self::$masterKey,
            false,
            self::$accountKey
        );
    }
}
