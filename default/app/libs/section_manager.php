<?php

class SectionManager
{
    private static $sections = [];

    public static function start($name)
    {
        ob_start();
    }

    public static function end($name)
    {
        self::$sections[$name] = ob_get_clean();
    }

    public static function yield($name)
    {
        echo self::$sections[$name] ?? '';
    }

    public static function section($name, $content = null)
    {
        if ($content !== null) {
            self::$sections[$name] = $content;
        } else {
            return isset(self::$sections[$name]) ? self::$sections[$name] : '';
        }
    }
}
