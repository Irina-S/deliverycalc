<?php

    /*
    * Plugin Name: Delivery Calculator
    * Description: Расчет стоимости доставки товара
    */

    add_action( 'wp_enqueue_scripts', 'add_styles_and_scripts' );

    /**
     * Подключение скриптов и стилей плагина
     */
    function add_styles_and_scripts() {

        wp_enqueue_script('jquery-1.7.2.min', plugins_url('deliverycalc/js/jquery-1.7.2.min.js'));
        
        wp_enqueue_style('jquery-ui-1.8.21', plugins_url('deliverycalc/css/jquery-ui-1.8.21.custom.css'));
        wp_enqueue_script('jquery-ui-1.8.21', plugins_url('deliverycalc/js/jquery-ui-1.8.21.custom.min.js'));

        wp_enqueue_style('form', plugins_url('deliverycalc/css/form.css')); 
        wp_enqueue_script('script', plugins_url('deliverycalc/js/script.js'));
        
    }

    add_action( 'woocommerce_after_single_product', 'woocust_custom_action', 5);

    /**
     * Получение данных о продукте и добавление формы
     */
    function woocust_custom_action() {
        global $product;
        $productWeight = $product->get_weight();

        $ajaxHandlerUri = plugins_url('deliverycalc/ajax.php');

        ob_start();
        require 'form.php';
        $form = ob_get_clean();
        echo $form;
    }
