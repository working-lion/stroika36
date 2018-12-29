$(document).ready(function () {
    $(".user_id").click(function(){
        var user_id = $(this).attr('rel');
        diafan_ajax.init({
            data:{
                action: "user",
                module: "specification",
                user_id: user_id
            },
            success: function(response) {
                alert(response.name);
            }
        });
    });
});
