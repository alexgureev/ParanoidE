!function( $ ){
    function setupLabel() {
        if ($('.label_check input').length) {
            $('.label_check').each(function(){
                $(this).removeClass('c_on');
                $(this).removeClass('btn-success');
                $(this).addClass('btn');
            });
            $('.label_check input:checked').each(function(){
                $(this).parent('label').addClass('c_on');
                $(this).removeClass('btn');
                $(this).parent('label').addClass('btn-success');
            });
        };
    };

    function testajax()
    {
        $.ajax({
            url: '/index.php?method=ajax_request',
            type: 'POST',
            data: 'ajax=some_vars',
            success: function(data) {
                //called when successful
                $('#ajax_div').html(data);
            },
            error: function(e) {
            //called when there is an error
            //console.log(e.message);
            }
        });
    }



    $(document).ready(function(){

        $('body').addClass('has-js');

        $('.ajaxtest').click(function(){
            testajax();
        });

        $('.label_check').click(function(){
            setupLabel();
        });

        $("#console-form1").submit(function ()
        {
            var str = $(this).serialize();
            $.ajax(
            {
                type: "POST",
                url: "/index.php?method=ajax_request",
                data: str,
                cache: false,
                success: function(data) {
                    //called when successful
                    $('#console').html(data);
                },
                error: function(e) {
                //called when there is an error
                //console.log(e.message);
                }
            });
            return false;
        });

        setupLabel();
    });

}( window.jQuery )

// Изменение адресной строки

var $url = document.getElementById('url'), $log = document.getElementById('log');

window.onpopstate = function(event) {
    var message =
    "onpopstate: "+
    "location: " + location.href + ", " +
    "data: " + JSON.stringify(event.state) +
    "\n\n"
    ;

    $url.innerHTML = location.href;
    $log.innerHTML += message;
    console.log(message);
};

window.onhashchange = function(event) {
    var message =
    "onhashchange: "+
    "location: " + location.href + ", "+
    "hash: " + location.hash +
    "\n\n"
    ;

    $url.innerHTML = location.href;
    $log.innerHTML += message;
    console.log(message);
};

// Prepare Buttons
var
buttons = document.getElementById('buttons'),
scripts = [
'history.pushState({state:1}, "State 1", "/api/?state=1");',
'history.pushState({state:2}, "State 2", "/api/?state=2");',
'history.replaceState({state:3}, "State 3", "/api.php?state=3");',
'location.hash = Math.random();',
'history.back();',
'history.forward();',
'document.location.href = document.location.href.replace(/[\#\?].*/,"");'
],
buttonsHTML = ''
;

// Add Buttons
for ( var i=0,n=scripts.length; i<n; ++i ) {
    var _script = scripts[i];
    buttonsHTML +=
        '<li><button data-shit=\'num-mum\' data-pip=\'ha-ha\' onclick=\'javascript:'+_script+'\'>'+_script+'</button></li>';
}
buttons.innerHTML = buttonsHTML;


