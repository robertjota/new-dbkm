<?php
// Robo launcher para default/
// Uso: php robo.php crud "campos" singular plural modulo
chdir(__DIR__); // C:\laragon\www\kumbiaphp\default

$vendorRobo = dirname(__DIR__) . '/vendor/bin/robo';

$args = array_slice($_SERVER['argv'], 1);
$cmd = 'php "' . $vendorRobo . '" ' . implode(' ', $args);

exec($cmd, $output, $return);

foreach ($output as $line) {
    echo $line . "\n";
}