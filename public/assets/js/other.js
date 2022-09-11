$(function(){
    $("#sub_btn").click(function(){
        var data = $('form').serializeArray();
        $.post($("form").data('href'), data, function(res){
            if (res.error_msg != '') {
                alert(res.error_msg);
            }else{
                window.location.href = $("form").data('redirect');
            }
        }, "json");
    });
});