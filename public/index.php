<pre>
<?php
/**
 * Recupera o primeiro parametro da url e prucura na pasta app/Controllers/ o arquivo correspondente.
 */
error_reporting(E_ALL);
ini_set('display_errors', 'On');

include_once realpath(__DIR__.'/../vendor').'/autoload.php';

define('PATH_APP',realpath(__DIR__.'/../app').'/');
define('PATH_VIEW',PATH_APP.'Views/');

$dir = dirname(explode('?', $_SERVER['REQUEST_URI'])[0]) . '/';
$dir = str_replace('//', '/', $dir);
if ($dir == '/')
    $file =  'Home';
else
    $file = trim(explode('/', substr($_SERVER['REQUEST_URI'], strlen($dir)))[0] ?? '');

empty(strpos($file, '.php')) && $file .= '.php';

App\Models\Base::initializeDB();

include realpath(__DIR__.'/../app/Controllers').'/'.ucfirst($file);
