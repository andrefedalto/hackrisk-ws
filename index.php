<?php
/**
 * Created by PhpStorm.
 * User: André
 * Date: 01/02/2015
 * Time: 15:46
 */

//1.1.x ae
ini_set('display_startup_errors',1);
ini_set('display_errors',1);
error_reporting(-1);

header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
header('Content-Type: application/json; charset=utf-8');

//header('Content-Type: text/html; charset=utf-8');
//

require_once('./libs/mysqli/MysqliDb.php');
require_once('./libs/dom/simple_html_dom.php');

require_once('libs/Slim/Slim.php');
\Slim\Slim::registerAutoloader();
$app = new \Slim\Slim();
$app->response->headers->set('Content-Type', 'application/json');
$app->response->headers->set('charset', 'utf-8');


$db = new Mysqlidb('db.utfapp.com', 'hackrisk', 'fXzx$=S6w.HU', 'hackrisk');
if(!$db) die("Database error");


require_once('./functions/functions.php');

require_once('./classes/Log.php');
$log = Log::getInstance();

require_once('./classes/DataManager.php');


$app->get(
    '/asd',
    function () use ($app, $db, $log)
    {
        $data = $db->get('prescriptions');


        $app->response()->status($log->getLastCode());

        buildOutput($data, $app->request()->params('debug'));

    }
);

$app->run();


