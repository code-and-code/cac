<?php
namespace Cac\Support;

class Log
{
    private static  $folder;
    private static  $file;

    private static function init()
    {
        self::$folder   = config('app.log.folder');
        self::$file     = config('app.log.file');
    }

    public static function getFile()
    {
        self::init();
        return self::$folder ."/".self::$file;
    }

    public static function logMsg($msg, $level = 'info')
    {
        $levelStr = '';

        switch ( $level )
        {
            case 'info':
                $levelStr = 'INFO';
                break;

            case 'warning':
                $levelStr = 'WARNING';
                break;

            case 'error':
                $levelStr = 'ERROR';
                break;
        }
        $date = date( 'Y-m-d H:i:s' );
        $msg = sprintf( "[%s] [%s]: %s%s", $date, $levelStr, $msg, PHP_EOL );
        file_put_contents( self::getFile(), $msg, FILE_APPEND );
    }
}

