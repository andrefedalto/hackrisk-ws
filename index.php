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
    '/',
    function () use ($app, $db, $log)
    {
        $app->response->headers->set('Content-Type', 'text/html');


        echo "welcome to clara!";

    }
);


$app->get(
    '/prescriptions',
    function () use ($app, $db, $log)
    {

        $db->join("drug d", "d.idDrug = p.idDrug", "LEFT");
        $data = $db->get ("prescription p", null, "p.*, d.*");


        $app->response()->status(200);

        buildOutput($data, $app->request()->params('debug'));

    }
);


$app->post(
    '/prescriptions',
    function () use ($app, $db, $log)
    {
        $data = json_decode($app->request->getBody(), true);


        $_drug = new DataManager('Drug');
        $_drug->setField('drugName', $data['drugName']);
        $_drug->save();

        unset($data['drugName']);

        $_prescription = new DataManager('Prescription');
        $_prescription->setField('idDoctor', 1);
        $_prescription->setField('idPatient', 1);
        $_prescription->setField('idDrug', $_drug->getField('idDrug'));
        $_prescription->setField('date', date("Y-m-d H:i:s"));
        $_prescription->setField('dose', $data['dose']);
        $_prescription->setField('doseUnit', $data['doseUnit']);
        $_prescription->setField('days', $data['days']);
        $_prescription->setField('frequency', $data['frequency']);
        $_prescription->setField('reason', $data['reason']);
        $_prescription->save();

        $db->join("drug d", "d.idDrug = p.idDrug", "LEFT");
        $db->where('p.idPrescription', $_prescription->getField('idPrescription'));
        $data = $db->get ("prescription p", null, "p.*, d.*");




        $app->response()->status(200);

        buildOutput($data, $app->request()->params('debug'));


    }
);


$app->put(
    '/prescriptions/:idPrescription',
    function ($idPrescription) use ($app, $db, $log)
    {

        $_prescription = new DataManager('Prescription');
        $_prescription->load($idPrescription);
        $_prescription->setField('start', $app->request->headers->get('startTime'));
        $_prescription->save();


        $db->join("drug d", "d.idDrug = p.idDrug", "LEFT");
        $db->where('p.idPrescription', $_prescription->getField('idPrescription'));
        $data = $db->get ("prescription p", null, "p.*, d.*");




        $app->response()->status(200);

        buildOutput($data, $app->request()->params('debug'));


    }
);


$app->post(
    '/exams',
    function () use ($app, $db, $log)
    {

        $_exam = new DataManager('Exam');
        $_exam->setField('idPatient', 1);
        $_exam->setField('examType', $app->request->headers->get('examType'));
        $_exam->setField('examValue', $app->request->headers->get('examValue'));
        $_exam->setField('date', date("Y-m-d H:i:s"));
        $_exam->save();


        $data = $_exam->getField();




        $app->response()->status(200);

        buildOutput($data, $app->request()->params('debug'));


    }
);



$app->post(
    '/feeling',
    function () use ($app, $db, $log)
    {

        if ($app->request->headers->get('feel') < 1)
            $feel = 1;
        else if ($app->request->headers->get('feel') > 10)
            $feel = 10;
        else
            $feel = $app->request->headers->get('feel');

        $_feeling = new DataManager('Feel');
        $_feeling->setField('idPatient', 1);
        $_feeling->setField('feel', $feel);
        $_feeling->setField('date', date("Y-m-d H:i:s"));
        $_feeling->save();


        $data = $_feeling->getField();




        $app->response()->status(200);

        buildOutput($data, $app->request()->params('debug'));


    }
);



$app->run();


