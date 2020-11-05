<?php

    ini_set('display_errors', 0);
    ini_set('display_startup_errors', 0);
    error_reporting(0);

    require_once 'functions.php';

    require_once 'dellin/dellin.php';

    require_once 'cdek/cdek.php';

    // если есть результаты хоть от одной компании
    if ((isset($dellinPrices) && count($dellinPrices)>0) || (isset($cdekPrices) && count($cdekPrices)>0)){
        $response['status'] = 'ok';
        $response['data']['companies'] = [];
    }
    // если ни у одной компании нет предложений
    else{
        $response = [
            "status"=>"error",
            "message"=>"Предложения не найдены"
        ];
    }

    if (isset($dellinPrices) && count($dellinPrices)>0){
        array_push($response['data']['companies'], [
            'name'=>'Деловые линии',
            'types'=>$dellinPrices
        ]);
    }

    if (isset($cdekPrices) && count($cdekPrices)>0){
        array_push($response['data']['companies'], [
            'name'=>'СДЭК',
            'types'=>$cdekPrices
        ]);
    }

    echo json_encode($response);
    exit();






    
    
