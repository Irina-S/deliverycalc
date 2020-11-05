<?php
    /**
     * Функция для отладки
     */
    function debug($data){
        echo "<pre>";
        var_dump($data);
        echo "</pre>";
    }


    /**
     * Запрос с отправкой данны к API
     */
    function get_data($url, $data){
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json')); 	
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));
        $res = curl_exec($curl);
        curl_close($curl);
        return json_decode($res, TRUE);
    }