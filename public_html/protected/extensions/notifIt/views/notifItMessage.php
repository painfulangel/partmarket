<?php
/**
 * Описание файла
 * 
 *
 * @author Moskvin Vitaliy <moskvinvitaliy@gmail.com>
 * @link http://moskvin-vitaliy.net/
 * @copyright Copyright &copy; 2013 Moskvin Vitaliy Software
 * @license GPL & MIT
 */
?>
<?php if(key($messages)==='message'):?>
<script type="text/javascript">
$(document).ready(function(){
    notif({
        msg: "<span class=\"glyphicon glyphicon-info-sign\"></span> <?php echo $messages['message'];?>",
        type: "info",
        width: 300,
        color: "#00008b",
        timeout: <?php echo strlen($messages['message'])*300;?>
    });

	/*setTimeout(function(){
        popup.close();
    }, 3000);*/
});
</script>
<?php elseif (key($messages)==='error'):?>
<script type="text/javascript">
$(document).ready(function(){
    notif({
        msg: "<span class=\"glyphicon glyphicon-warning-sign\"></span> <?php echo $messages['error'];?>",
        type: "error",
        width: 300,
        color: "#890505",
        timeout: <?php echo strlen($messages['error'])*300;?>
    });

	/*setTimeout(function(){
        popup.close();
    }, 3000);*/
});
</script>
<?php elseif (key($messages)==='success'):?>
<script type="text/javascript">
$(document).ready(function(){
    notif({
        msg: "<span class=\"glyphicon glyphicon-ok-sign\"></span> <?php echo $messages['success'];?>",
        type: "success",
        width: 300,
        color: "#006400",
        timeout: <?php echo strlen($messages['success'])*300;?>
    });

	/*setTimeout(function(){
        popup.close();
    }, 3000);*/
});
</script>
<?php endif;?>
