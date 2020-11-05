<?php

/**
 * Ключ авторизации для API Деловы линий
 */
$dellinAppKey = "B01B3FFD-A4B0-4891-9887-4E71E1AE4265";


/**
 * Возвращает код грода по названию или FALSE
 */
function get_city_code_by_name($name){
    global $dellinAppKey;
    $dellinCityCodeUrl = 'https://api.dellin.ru/v2/public/kladr.json';
    $dellinCityCodeData = [ 
        "appkey"=>$dellinAppKey,
        "limit"=>1,
        "q"=>$name
     ];
    
    // $dellinCityCodeData['q'] = $name;
    $res = get_data($dellinCityCodeUrl, $dellinCityCodeData);

    if (!empty($res['cities'])){
        $cityCode = $res['cities'][0]['code'];
        return $cityCode;
    }
    else{
        return FALSE;
    }
}

/**
 * Возвращает код случайного терминала по коду города
 */
function get_first_terminal_by_direction($code){
    global $dellinAppKey;
    $delllinTerminalIdUrl = 'https://api.dellin.ru/v1/public/request_terminals.json';
    // $delllinTerminalIdData = require('delllinTerminalIdData.php');

    $delllinTerminalIdData = [  
        "appkey"=>$dellinAppKey,
        "code"=>$code
    ];

    // $delllinTerminalIdData['code'] = $code;
    $res = get_data($delllinTerminalIdUrl, $delllinTerminalIdData);

    if (!empty($res['terminals'])){
        return $res['terminals'][0]['id'];
    }
    else{
        return FALSE;
    }        
}


/**
 * URL калькулятора Деловых линий
 */
$dellinCalculatorUrl = 'https://api.dellin.ru/v2/calculator.json';
/**
 * Данные для калькулятора Деловых линий
 */
$dellinCalculatorData = require('dellinCalculatorData.php');

$dellinCalculatorData['appkey'] = $dellinAppKey;

// ОБРАБОТКА ПАРАМЕТРОВ ЗАБОРА ГРУЗА

// если выбран режим "от двери"
if (isset($_POST['is-from-door'])){
    $dellinCalculatorData['delivery']['derival']['variant'] = 'address';
    $dellinCalculatorData['delivery']['derival']['address']['search'] = $_POST['derival-city'].', '.$_POST['derival-addr'];
}
// если доставка от терминала
else{
    $derivalCityCode = get_city_code_by_name($_POST['derival-city']);
    if (!$derivalCityCode){
        $response = [
            "status"=>"error",
            "message"=>"Не найден город отправления!"
        ];
        echo json_encode($response);
        exit();
    }
    $derivalTerminalID = get_first_terminal_by_direction($derivalCityCode);
    $dellinCalculatorData['delivery']['derival']['variant'] = 'terminal';
    $dellinCalculatorData['delivery']['derival']['terminalID'] = $derivalTerminalID;
}

// ОБРАБОТКА ПАРАМЕТРОВ ДОСТАВКИ ГРУЗА

// если выбран режим "до двери"
if (isset($_POST['is-to-door'])){
    $dellinCalculatorData['delivery']['arrival']['variant'] = 'address';
    $dellinCalculatorData['delivery']['arrival']['address']['search'] = $_POST['arrival-city'].', '.$_POST['arrival-addr'];
}
// если доставка до терминала
else{
    $arrivalCityCode = get_city_code_by_name($_POST['arrival-city']);
    if (!$arrivalCityCode){
        $response = [
            "status"=>"error",
            "message"=>"Не найден город получателя!"
        ];
        echo json_encode($response);
        exit();
    }
    $arrivalTerminalID = get_first_terminal_by_direction($arrivalCityCode);
    $dellinCalculatorData['delivery']['arrival']['variant'] = 'terminal'; 
    $dellinCalculatorData['delivery']['arrival']['terminalID'] = $arrivalTerminalID;
}

// ПАРАМЕТРЫ ТОВАРА

$dellinCalculatorData['delivery']['derival']['produceDate'] = $_POST['derival-date'];
$dellinCalculatorData['cargo']['totalWeight'] = $_POST['product-weight'];
$dellinCalculatorData['cargo']['totalVolume'] = $_POST['product-volume'];
// проверка на негабаритный товар(по массе)
if ($_POST['product-weight']>20){
    $dellinCalculatorData['cargo']['oversizedWeight'] = $_POST['product-weight'];
    $dellinCalculatorData['cargo']['oversizedVolume'] = $_POST['product-volume'];
}
    
$dellinCalculatorResult = get_data($dellinCalculatorUrl, $dellinCalculatorData);

/**
 * Достпне варианты доставки от Деловы линий
 */
$dellinPrices = [];
if ($dellinCalculatorResult['metadata']['status']==200){
    foreach ($dellinCalculatorResult['data']['availableDeliveryTypes'] as $key=>$value){
        switch ($key){
                case 'auto':
                    $deliveryType = 'авто';
                break;
                case 'express':
                    $deliveryType = 'экспресс';
                break;
                case 'letter':
                    $deliveryType = 'письмо';
                break;
                case 'avia':
                    $deliveryType = 'авиа';
                break;
                case 'small':
                    $deliveryType = 'малогабаритный груз';
                break;
        }
        array_push($dellinPrices, [
            'type'=>$deliveryType,
            'price'=>$value
        ]);
    }    
}