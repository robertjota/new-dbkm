<?php

class Flash
{
    private static $_contentMsj = array();

    public static function set($name, $msg, $audit = FALSE)
    {
        if (self::hasMessage()) {
            self::$_contentMsj = Session::get('flash_message');
        }

        if (isset($_SERVER['SERVER_SOFTWARE'])) {
            $icon = ($name === 'danger') ? 'error' : $name;

            self::$_contentMsj[] = "
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    const Toast = Swal.mixin({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 3000,
                        timerProgressBar: true,
                        didOpen: (toast) => {
                            toast.addEventListener('mouseenter', Swal.stopTimer)
                            toast.addEventListener('mouseleave', Swal.resumeTimer)
                        }
                    });

                    Toast.fire({
                        icon: '{$icon}',
                        title: '{$msg}'
                    });
                });
            </script>";
        } else {
            self::$_contentMsj[] = $name . ': ' . Filter::get($msg, 'striptags') . PHP_EOL;
        }

        Session::set('flash_message', self::$_contentMsj);

        if ($audit) {
            if ($name == 'success') {
                DwAudit::debug($msg);
            } else if ($name == 'danger') {
                DwAudit::error($msg);
            } else {
                DwAudit::$name($msg);
            }
        }
    }

    public static function hasMessage()
    {
        return Session::has('flash_message') ? TRUE : FALSE;
    }

    public static function clean()
    {
        self::$_contentMsj = array();
        Session::delete('flash_message');
    }

    public static function output()
    {
        if (Flash::hasMessage()) {
            $tmp = Session::get('flash_message');
            foreach ($tmp as $msg) {
                echo $msg;
            }
            self::clean();
        }
    }

    public static function toString()
    {
        $tmp = self::hasMessage() ? Session::get('flash_message') : array();
        $msg = array();

        foreach ($tmp as $item) {
            $item = explode('<script', $item);
            if (!empty($item[0])) {
                $msg[] = Filter::get($item[0], 'striptags');
            }
        }

        $flash = Filter::get(ob_get_clean(), 'striptags', 'trim');
        $msg = Filter::get(join('<br />', $msg), 'trim');
        self::clean();

        return ($flash) ? $flash . '<br />' . $msg : $msg;
    }

    public static function error($msg, $audit = FALSE)
    {
        self::set('danger', $msg, $audit);
    }

    public static function warning($msg, $audit = FALSE)
    {
        self::set('warning', $msg, $audit);
    }

    public static function info($msg, $audit = FALSE)
    {
        self::set('info', $msg, $audit);
    }

    public static function valid($msg, $audit = FALSE)
    {
        self::set('success', $msg, $audit);
    }
}
