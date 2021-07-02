<div style="display: none">
    <script async="async" src="https://www.paypalobjects.com/js/external/paypal-button.min.js?merchant=<?php echo $model->system_login ?>" 
            data-button="buynow" 
            data-name="<?php echo $model->description ?> " 
            data-quantity="1" 
            data-amount="<?php echo $model->total_value ?>" 
            data-currency="RUB" 
            data-shipping="0.0" 
            data-tax="0.0" 

    ></script>
</div>

<script>

    var f = function () {
        document.getElementsByClassName('paypal-button')[0].submit();
        clearInterval(inter);
    }
    var inter = setInterval(f, 3000);
</script>