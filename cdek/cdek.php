<?php
    require 'CalculatePriceDeliveryCdek.php';

    /**
     * Список тарифов ТК СДЭК
     */
    $cdekTariffList = require('cdekTariffList.php');

    /**
     * Доступные варианты от СДЭК
     */
    $cdekPrices = [];

    // определяем режим доставки, по введенным данным
    $modeDeliveryId = 4;// по-умолчанию режим доставки склад-склад
    if (!isset($_POST['is-from-door']) && !isset($_POST['is-to-door']))
        $modeDeliveryId = 4;//склад-склад
    else if (!isset($_POST['is-from-door']) && isset($_POST['is-to-door']))
        $modeDeliveryId = 3;//склад-дверь
    else if (isset($_POST['is-from-door']) && !isset($_POST['is-to-door']))
        $modeDeliveryId = 2;//дверь-склад
    else if (isset($_POST['is-from-door']) && isset($_POST['is-to-door']))
        $modeDeliveryId = 1;//дверь-дверь

    // проверяем возможность доставки каждым тарифом
    foreach($cdekTariffList as $key => $value){
        try {

            //создаём экземпляр объекта CalculatePriceDeliveryCdek
            $calc = new CalculatePriceDeliveryCdek();
            
            //устанавливаем город-отправитель
            $calc->setSenderCityId($_POST['derival-city-cdek-code']);
            //устанавливаем город-получатель
            $calc->setReceiverCityId($_POST['arrival-city-cdek-code']);
            //устанавливаем дату планируемой отправки
            $calc->setDateExecute($_POST['derival-date']);
            //задаём список тарифов с приоритетами
            $calc->addTariffPriority($key);
            //устанавливаем режим доставки
            $calc->setModeDeliveryId($modeDeliveryId);
            // добавление параметров товара
            $calc->addGoodsItemByVolume($_POST['product-weight'], $_POST['product-volume']);
            
            if ($calc->calculate() === true) {
                $res = $calc->getResult();
                array_push($cdekPrices, [
                    'type'=>$value,
                    'price'=>$res['result']['price']
                ]);
            }
        } 
        catch (Exception $e) {
        }
    }