<pre>
<?php
/**
 * Recupera o primeiro parametro da url e prucura na pasta app/Controllers/ o arquivo correspondente.
 */
error_reporting(E_ALL);
ini_set('display_errors', 'On');

include_once realpath(__DIR__ . '/../vendor') . '/autoload.php';

define('PATH_APP', realpath(__DIR__ . '/../app') . '/');
define('PATH_VIEW', PATH_APP . 'Views/');

echo '<pre>';
$r = dirname($_SERVER['SCRIPT_NAME']);
if ($r == '/') {
    $dir = explode('?', $_SERVER['REQUEST_URI'])[0];
} else {
    $p = '/^' . preg_quote($r, '/') . '/';
    $dir = preg_replace($p, '', $_SERVER['REQUEST_URI'], 1);
    $dir = explode('?', $dir)[0];
}
$dir = trim($dir, '/') . '/';
$dir = str_replace('//', '/', $dir);
$file = $dir == '/' ? 'Home' : explode('/', $dir)[0];;

empty(strpos($file, '.php')) && $file .= '.php';

App\Models\Base::initializeDB();

include realpath(__DIR__ . '/../app/Controllers') . '/' . ucfirst($file);
