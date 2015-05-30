<?php
/**
 * Created by PhpStorm.
 * User: André
 * Date: 01/04/2015
 * Time: 13:57
 */

function buildOutput($data, $debug = false)
{
    $log = Log::getInstance();
    $db = MysqliDb::getInstance();

    $output = array();

    if ($log->countErrors() > 0)
        $errors = $log->getErrors();

    $output = $data;

    if (isset($errors) && sizeof($errors) > 0)
        $output['_ERROR_'] = $errors;

    if ($debug == 'true')
        $output['_DEBUG_'] = $log->getLogs();

    $_log = array(
        'quem'      => getUserIP(),
        'quando'    => date("Y-m-d H:i:s"),
        'onde'      => $_SERVER['REQUEST_URI'],
        'data'      => json_encode($log->getLogs())
    );

//    $db->insert('log', $_log);

    echo json_encode($output, JSON_PRETTY_PRINT);
}


function getUserIP()
{
    $client  = @$_SERVER['HTTP_CLIENT_IP'];
    $forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];
    $remote  = $_SERVER['REMOTE_ADDR'];

    if(filter_var($client, FILTER_VALIDATE_IP))
    {
        $ip = $client;
    }
    elseif(filter_var($forward, FILTER_VALIDATE_IP))
    {
        $ip = $forward;
    }
    else
    {
        $ip = $remote;
    }

    return $ip;
}

function createSalt($size = 16)
{
    $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789,.<>;:()*&%$#@!";
    $output = "";
    for ($i = 0; $i < $size; $i++)
        $output .= $chars[rand(0, strlen($chars)-1)];

    return $output;
}


function toUtf8($str, $from = null)
{
    if ($from == null)
        $str = iconv(mb_detect_encoding($str), "UTF-8", $str);
    else
        $str = iconv($from, "UTF-8", $str);

    $str = str_replace("&nbsp;", "", $str);

    return trim($str);
}

function getCampusId($ref)
{
    $db = MysqliDb::getInstance();

    $db->where("referencia", $ref);
    $campus = $db->getOne("campus");

    if ($campus != null)
        return $campus['idCampus'];
    else
        return 1;
}

function getCampusRef($id)
{
    $db = MysqliDb::getInstance();

    $db->where("idCampus", $id);
    $campus = $db->getOne("campus");

    if ($campus != null)
        return $campus['referencia'];
    else
        return '01';
}

function getInicioFromCodigo($codigo)
{
    $codigo = substr($codigo, 1, 2);
    switch($codigo)
    {
        case "M1":
            return "07h30";
        case "M2":
            return "08h20";
        case "M3":
            return "09h10";
        case "M4":
            return "10h20";
        case "M5":
            return "11h10";
        case "M6":
            return "12h00";
        case "T1":
            return "13h00";
        case "T2":
            return "13h50";
        case "T3":
            return "14h40";
        case "T4":
            return "15h50";
        case "T5":
            return "16h40";
        case "T6":
            return "17h50";
        case "N1":
            return "18h40";
        case "N2":
            return "19h30";
        case "N3":
            return "20h20";
        case "N4":
            return "21h20";
        case "N5":
            return "22h10";
    }
}

function getTerminoFromCodigo($codigo)
{
    $codigo = substr($codigo, 1, 2);
    switch($codigo)
    {
        case "M1":
            return "08h20";
        case "M2":
            return "09h10";
        case "M3":
            return "10h00";
        case "M4":
            return "11h10";
        case "M5":
            return "12h00";
        case "M6":
            return "12h50";
        case "T1":
            return "13h50";
        case "T2":
            return "14h40";
        case "T3":
            return "15h30";
        case "T4":
            return "16h40";
        case "T5":
            return "17h30";
        case "T6":
            return "18h40";
        case "N1":
            return "19h30";
        case "N2":
            return "20h20";
        case "N3":
            return "21h10";
        case "N4":
            return "22h10";
        case "n5":
            return "23h00";
    }
}

function sortHorarios($horarios)
{
    /**
     * Funcao POG feita nas coxa, mas funciona
     */

    $h = array(
        "2" => array("M" => array(), "T" => array(), "N" => array()),
        "3" => array("M" => array(), "T" => array(), "N" => array()),
        "4" => array("M" => array(), "T" => array(), "N" => array()),
        "5" => array("M" => array(), "T" => array(), "N" => array()),
        "6" => array("M" => array(), "T" => array(), "N" => array()),
        "7" => array("M" => array(), "T" => array(), "N" => array())
    );
    foreach($horarios as $horario)
    {
        $dia = substr($horario['codigo'], 0, 1);
        $turno = substr($horario['codigo'], 1, 1);
        if (!isset($h[$dia][$turno]))
            $h[$dia][$turno] = array();
        array_push($h[$dia][$turno], $horario);
    }
    $a = $h;
    foreach($a as $k => $v)
        $h[$k] = array_merge($v["M"], $v["T"], $v["N"]);

    $output = array_merge($h["2"], $h["3"], $h["4"], $h["5"], $h["6"], $h["7"]);

    return $output;
}

function getDiaFromCodigo($codigo)
{
    $dia = substr($codigo, 0, 1);
    switch ($dia)
    {
        case "2":
            return "Segunda";
        case "3":
            return "Terça";
        case "4":
            return "Quarta";
        case "5":
            return "Quinta";
        case "6":
            return "Sexta";
        case "7":
            return "Sábado";
    }
}