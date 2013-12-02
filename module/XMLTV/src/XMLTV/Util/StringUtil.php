<?php
/*
 * Mizanur Rahman
 *
 * @link      https://www.linkedin.com/pub/mizanur-rahman/32/b15/248
 * @copyright N/A
 */

namespace XMLTV\Util;

class StringUtil
{
    public static function decamelize($camel, $splitter = "_")
    {
        $camel=preg_replace('/(?!^)[[:upper:]][[:lower:]]/', 
            '$0', preg_replace('/(?!^)[[:upper:]]+/', $splitter.'$0', $camel)
        );
        return strtolower($camel); 
    }
}
