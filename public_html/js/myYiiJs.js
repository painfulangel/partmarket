function ShowWindow(title, text) {
    document.getElementById('modalTitle').innerHTML = title;
    document.getElementById('modalText').innerHTML = text;
    $('#modalCartButtons').hide();

    $('#myModal').reveal({
        animation: 'fadeAndPop', //fade, fadeAndPop, none
        animationspeed: 300, //how fast animtions are
        closeonbackgroundclick: true, //if you click background will modal close?
        dismissmodalclass: 'close-reveal-modal'    //the class of a button or element that will close an open modal
    });
}
function iFrameShowWindow(title, text, cart) {
    parent.document.getElementById("modalTitle").innerHTML = title;
    parent.document.getElementById("modalText").innerHTML = text;
    if (cart) {
        $('#modalCartButtons').show();
    } else
        $('#modalCartButtons').hide();
    parent.$("#myModal").reveal({
        animation: "fadeAndPop", //fade, fadeAndPop, none
        animationspeed: 300, //how fast animtions are
        closeonbackgroundclick: true, //if you click background will modal close?
        dismissmodalclass: "close-reveal-modal"    //the class of a button or element that will close an open modal
    });

}

function ShowSubmitPrePayWindow() {


    $('#myModalSubmit').reveal({
        animation: 'fadeAndPop', //fade, fadeAndPop, none
        animationspeed: 300, //how fast animtions are
        closeonbackgroundclick: true, //if you click background will modal close?
        dismissmodalclass: 'close-reveal-modal'    //the class of a button or element that will close an open modal
    });
}

function windowSize(){
    if ($(window).width() <= '640'){
        $('#request-wu-form, #request-get-price-form').removeClass('form-horizontal');
    } else {
        $('#request-wu-form, #request-get-price-form').addClass('form-horizontal');
    }
}
$(window).on('load resize',windowSize);

function displayMenu() {
    $('.inner-leftmm').slideToggle();
}

function checkTop() {
    var posElement = $('#leftmm').offset().top;
    var scrollDoc = $(document).scrollTop();
    if(scrollDoc > posElement){
        $('.spoiler-leftmm').css('position','fixed').css('top', '0px');
    }else {
        $('.spoiler-leftmm').css('position','relative').css('top', '');
    }
}