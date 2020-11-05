<a class="popup-open" href="#">Калькулятор стоимости доставки</a>
<div class="popup-fade">	
    <form action=<?=isset($ajaxHandlerUri)?$ajaxHandlerUri:''?> class="calc-form popup" id="calc-form">
        <a class="popup-close" href="#">x</a>
        <div class="calc-form__header">Калькулятор стоимости доставки</div>
        <div class="calc-form-inputs">
            <div class="calc-form-inputs-row1">
                <div class="calc-form-inputs-col1">
                    <label for="derival-city">Откуда:</label>
                    <input type="text" class="calc-form__input_txt" id="derival-city" name="derival-city">
                    
                    <input type="checkbox" class="calc-form__input_checkbox" id="is-from-door" name="is-from-door">
                    <label for="is-from-door">Забрать у отправителя</label>

                    <label for="derival-addr">Адрес отправителя:</label>
                    <input type="text" class="calc-form__input_txt" id="derival-addr" name="derival-addr">
                </div>
                <div class="calc-form-inputs-col2">
                    <label for="arrival-city">Куда:</label>
                    <input type="text" class="calc-form__input_txt" id="arrival-city" name="arrival-city">
                    
                    <input type="checkbox" class="calc-form__input_checkbox" id="is-to-door" name="is-to-door">
                    <label for="is-to-door">Доставка до двери</label>

                    <label for="arrival-addr">Адрес получателя:</label>
                    <input type="text" class="calc-form__input_txt" id="arrival-addr" name="arrival-addr"> 
                </div>
            </div>
            <div class="calc-form-inputs-row2">
                <div class="calc-form-inputs-col1">
                    <label for="product-weight">Вес(кг):</label>
                    <input type="text" class="calc-form__input_txt" id="product-weight" name="product-weight" value=<?=isset($productWeight)?$productWeight:''?>>
                    <input type="hidden" name="product-volume" value="1">
                </div>
                <div class="calc-form-inputs-col2">
                    <label for="derival-date">Дата отправки:</label>
                    <input type="date" class="calc-form__input_txt" id="derival-date" name="derival-date" min=<?=date("Y-m-d") ?> value=<?=date("Y-m-d") ?>>
                </div>    
            </div>
            <div class="calc-form-inputs-row3">
                <button type="submit" class="calc-form__input_submit">Рассчитать</button>
            </div>    
        </div> 
        <div class="calc-form-error" id ="calc-form-error" ></div>
        <div class="calc-form-result" id="calc-form-result">
            <table class="result-table" id="result-table">
                <thead>
                    <th>ТК</th>
                    <th>Тип перевозки</th>
                    <th>Цена</th>
                </thead>
                <tbody id="result-table-body">
                </tbody>
            </table>
        </div>
    </form>
</div>


