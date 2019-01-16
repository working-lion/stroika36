$(document).on('click', '#push_to_catr_btn', function (e) {
    e.preventDefault();

    var res = confirm('Внимание! Текущая корзина будет удалена. Продолжить?');
    
    if(res) {
        $('#js_push_to_cart_form').submit();
    }
});

$(document).on('click', '#remove_spec_btn', function (e) {
    e.preventDefault();

    var res = confirm('Внимание! Текущая спецификация будет удалена. Продолжить?');

    if(res) {
        $('#js_push_to_cart_form_action').val('remove');
        $('#js_push_to_cart_form').submit();
    }
});