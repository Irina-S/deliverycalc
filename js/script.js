$(function(){

    //Отправка ajax-запроса цен
    function mainQuery(){
        $.ajax({
            url:$('#calc-form').attr('action'),
            type:'POST',
            data:$('#calc-form').serialize(),
            beforeSend:loader
        }).always(function(response){
            $('#calc-form :submit').html('Рассчитать');
        }).fail(function(){
            $('#calc-form-error').text('Не удалось отправить запрос');
            $('#calc-form-error').fadeIn();
        }).done(function(response){
            response = JSON.parse(response);
            if (response['status']=='error'){
                $('#calc-form-error').text(response['message']);
                $('#calc-form-error').fadeIn();
            }
            else if (response['status']=='ok'){
                let companies = response['data']['companies'];
                $resTableBody = $('#result-table-body');
                $resTableBody.empty();
                companies.forEach(function(company){
                    $resTableBody.append('<tr></tr>');
                    $resTableBodyTr = $resTableBody.find('tr').last();
                    $resTableBodyTr.append('<td rowspan="'+company['types'].length+'">'+company['name']+'</td>');
                    $resTableBodyTr.append('<td>'+company['types'][0]['type']+'</td>');
                    $resTableBodyTr.append('<td>'+company['types'][0]['price']+'</td>');
                    company['types'].forEach(function(type, index){
                        if (index>0){
                            $resTableBody.append('<tr></tr>');
                            $resTableBodyTr = $resTableBody.find('tr').last();
                            $resTableBodyTr.append('<td>'+company['types'][index]['type']+'</td>');
                            $resTableBodyTr.append('<td>'+company['types'][index]['price']+'</td>');
                        }
                    })
                });
                $('#result-table').fadeIn();
            }
        })
    }



    // Автокмомплит для города-отправления
    $("#derival-city").autocomplete({
	    source: function(request,response) {
	      $.ajax({
	        url: "http://api.cdek.ru/city/getListByTerm/jsonp.php?callback=?",
	        dataType: "jsonp",
	        data: {
	        	q: function () { return $("#derival-city").val() },
	        	name_startsWith: function () { return $("#derival-city").val() }
	        },
	        success: function(data) {
	          response($.map(data.geonames, function(item) {
	            return {
	              label: item.cityName,
	              value: item.cityName,
	              id: item.id
	            }
	          }));
	        }
	      });
	    },
	    minLength: 1,
	    select: function(event,ui) {
            $('#derival-city-cdek-code').remove();
            $inputHidden = $('<input name="derival-city-cdek-code" id="derival-city-cdek-code" value="'+ui.item.id+'" hidden>');
            $('#derival-city').after($inputHidden);
	    }
	  });

    // Автокмомплит для города-назначения
    $("#arrival-city").autocomplete({
	    source: function(request,response) {
	      $.ajax({
	        url: "http://api.cdek.ru/city/getListByTerm/jsonp.php?callback=?",
	        dataType: "jsonp",
	        data: {
	        	q: function () { return $("#arrival-city").val() },
	        	name_startsWith: function () { return $("#arrival-city").val() }
	        },
	        success: function(data) {
	          response($.map(data.geonames, function(item) {
	            return {
	              label: item.cityName,
	              value: item.cityName,
	              id: item.id
	            }
	          }));
	        }
	      });
	    },
	    minLength: 1,
	    select: function(event,ui) {
            $('#arrival-city-cdek-code').remove();
            $inputHidden = $('<input name="arrival-city-cdek-code" id="arrival-city-cdek-code" value="'+ui.item.id+'" hidden>');
            $('#arrival-city').after($inputHidden);
	    }
	  });

   
    //Отправка формы
    $('#calc-form').on('submit', function(event){
        event.preventDefault();

        $('#result-table').fadeOut();
        $('#calc-form-error').fadeOut();

        if ($('#derival-city').val()==''){
            $('#calc-form-error').text('Заполните поле "откуда"');
            $('#calc-form-error').fadeIn();
        }
        else if($('#is-from-door').is(':checked') && $('#derival-addr').val()==''){
            $('#calc-form-error').text('Заполните поле "адрес отправителя"');
            $('#calc-form-error').fadeIn();
        }
        else if ($('#arrival-city').val()==''){
            $('#calc-form-error').text('Заполните поле "куда"');
            $('#calc-form-error').fadeIn();
        }
        else if($('#is-to-door').is(':checked') && $('#arrival-addr').val()==''){
            $('#calc-form-error').text('Заполните поле "адрес получателя"');
            $('#calc-form-error').fadeIn();
        }
        else if ($('#product-weight').val()==''){
            $('#calc-form-error').text('Поле "вес" не заполнено');
            $('#calc-form-error').fadeIn();
        }
        else if (!$.isNumeric($('#product-weight').val())){
            $('#calc-form-error').text('Поле "вес" не явялется числом');
            $('#calc-form-error').fadeIn();
        }
        else if ($('#derival-date').val()==''){
            $('#calc-form-error').text('Заполните поле "дата отправления"');
            $('#calc-form-error').fadeIn();
        }
        else{
            mainQuery();
        }
    })


    
    // Функции для модального окна(формы)

    // Открытие по ссылке
    $('.popup-open').click(function() {
        $(this).fadeOut();
        $('.popup-fade').fadeIn();
		return false;
	});	
	
    // Клик по ссылке "Закрыть".
	$('.popup-close').click(function() {
        $(this).parents('.popup-fade').fadeOut();
        $('.popup-open').fadeIn();
		return false;
	});        
 
	// Закрытие по клавише Esc.
	$(document).keydown(function(e) {
		if (e.keyCode === 27) {
			e.stopPropagation();
            $('.popup-fade').fadeOut();
            $('.popup-open').fadeIn();
		}
	});
	
	// Клик по фону, но не по окну.
	$('.popup-fade').click(function(e) {
		if ($(e.target).closest('.popup').length == 0) {
            $(this).fadeOut();	
            $('.popup-open').fadeIn();				
		}
    });	
    


    // Функции для анимации загрузки

    function loader(){      
        let loaderHtml = '<div class="loader-container arc-rotate"><div class="loader"><div class="arc"></div></div></div>';
        $('#calc-form :submit').html(loaderHtml);
    }

})