<?php
/**
 * Created by PhpStorm.
 * User: André
 * Date: 13/03/2015
 * Time: 14:11
 */


$app->get(
    '/aluno',
    function () use ($app, $db, $log)
    {
        if ($app->request()->params('forceUpdate') == 'true')
            $forceUpdate = true;
        else
            $forceUpdate = false;

        $aluno = new Aluno($app->request->headers->get('Auth-Aluno'));
        $aluno->forceUpdate($forceUpdate);
        $data = $aluno->buildAluno();


        $app->response()->status($log->getLastCode());

        buildOutput($data, $app->request()->params('debug'));

    }
);

$app->get(
    '/aluno/:idCurso',
    function ($idCurso) use ($app, $db, $log)
    {
        if ($app->request()->params('forceUpdate') == 'true')
            $forceUpdate = true;
        else
            $forceUpdate = false;

        $aluno = new Aluno($app->request->headers->get('Auth-Aluno'));
        $aluno->forceUpdate($forceUpdate);
        $data = $aluno->buildCurso($idCurso);


        $app->response()->status($log->getLastCode());

        buildOutput($data, $app->request()->params('debug'));

    }
);

$app->get(
    '/aluno/:idCurso/boletim',
    function ($idCurso) use ($app, $db, $log)
    {
        if ($app->request()->params('forceUpdate') == 'true')
            $forceUpdate = true;
        else
            $forceUpdate = false;

        $aluno = new Aluno($app->request->headers->get('Auth-Aluno'));
        $aluno->forceUpdate($forceUpdate);
        $data = $aluno->buildBoletim($idCurso);


        $app->response()->status($log->getLastCode());

        buildOutput($data, $app->request()->params('debug'));
    }
);

$app->get(
    '/aluno/:idCurso/grade',
    function ($idCurso) use ($app, $db, $log)
    {
        if ($app->request()->params('forceUpdate') == 'true')
            $forceUpdate = true;
        else
            $forceUpdate = false;

        if ($app->request()->params('gradeCompleta') == true)
            $full = true;
        else
            $full = false;

        $aluno = new Aluno($app->request->headers->get('Auth-Aluno'));
        $aluno->forceUpdate($forceUpdate);
        $data = $aluno->buildGrade($idCurso, $full);


        $app->response()->status($log->getLastCode());

        buildOutput($data, $app->request()->params('debug'));

    }
);

$app->get(
    '/aluno/:idCurso/historico',
    function ($idCurso) use ($app, $db, $log)
    {
        if ($app->request()->params('forceUpdate') == 'true')
            $forceUpdate = true;
        else
            $forceUpdate = false;


        $aluno = new Aluno($app->request->headers->get('Auth-Aluno'));
        $aluno->forceUpdate($forceUpdate);
        $data = $aluno->buildHistorico($idCurso);


        $app->response()->status($log->getLastCode());

        buildOutput($data, $app->request()->params('debug'));

    }
);

$app->get(
    '/aluno/:idCurso/matriz',
    function ($idCurso) use ($app, $db, $log)
    {
        if ($app->request()->params('forceUpdate') == 'true')
            $forceUpdate = true;
        else
            $forceUpdate = false;


        $aluno = new Aluno($app->request->headers->get('Auth-Aluno'));
        $aluno->forceUpdate($forceUpdate);
        $data = $aluno->buildMatriz($idCurso);


        $app->response()->status($log->getLastCode());

        buildOutput($data, $app->request()->params('debug'));

    }
);

$app->get(
    '/aluno/:idCurso/all',
    function ($idCurso) use ($app, $db, $log)
    {

        if ($app->request()->params('forceUpdate') == 'true')
            $forceUpdate = true;
        else
            $forceUpdate = false;

        if ($app->request()->params('gradeCompleta') == true)
            $full = true;
        else
            $full = false;

        $aluno = new Aluno($app->request->headers->get('Auth-Aluno'));
        $aluno->forceUpdate($forceUpdate);
        $data = $aluno->buildAluno();
        unset($data['cursos']);
        $data['curso'] = $aluno->buildCurso($idCurso);
        $data['boletim'] = $aluno->buildBoletim($idCurso);
        $data['gradeHoraria'] = $aluno->buildGrade($idCurso, $full);
        $data['historico'] = $aluno->buildHistorico($idCurso);


        $app->response()->status($log->getLastCode());

        buildOutput($data, $app->request()->params('debug'));
    }
);


















//
//$app->get(
//    '/professores',
//    function () use ($app, $db, $log)
//    {
//
//
//        $aluno = new Aluno($app->request->headers->get('Auth-Aluno'));
//        $data = $aluno->professores();
//
//
//        $app->response()->status($log->getLastCode());
//
//        buildOutput($data, $app->request()->params('debug'));
//    }
//);
//
//
//$app->get(
//    '/professor/:ano/:semestre',
//    function ($ano, $semestre) use ($app, $db, $log)
//    {
//
//
//        $aluno = new Aluno($app->request->headers->get('Auth-Aluno'));
//        $data = $aluno->professor($ano, $semestre);
//
//
//        $app->response()->status($log->getLastCode());
//
//        if ($data != false)
//            header("Refresh: 0;");
//
//        buildOutput($data, $app->request()->params('debug'));
//    }
//);
//
//
//
//





