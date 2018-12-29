/*  $(window).resize(function () {

var widt = screen.width;
bla(widt);
});

$(window).trigger('resize');   

diafan_ajax.success['cart_recalc'] = function(form, response){
	var widt = $(window).width();
	bla(widt);	
}

$(window).resize(function () {
	var widt = $(window).width();
	bla(widt);
});


function bla(widt){  
if(widt<=800)
{
$('.cart th.cart_name, table.wishlist tr th.wish_name').text('Наименование');
$('.cart th.cart_count, table.wishlist tr th.wish_count, table.cart.resulya thead tr th.cart_count').text('Кол-во');
$('table.cart.resulya th.cart_summ').text('Сумма');
//$('.cart').find('.cart_last_trr .cart_totalr').attr('colSpan', 3);
$('.cart').find('.cart_delivery').attr('colSpan', 3);
$('tr.wishlist_last_trr td.wishlist_totalr').attr('colSpan', 4);
$('table.user_order').find('th.sum').text('Сумма');
} 
else
{
$('.cart th.cart_name, table.wishlist tr th.wish_name').text('Наименование товара');
$('.cart th.cart_count, table.wishlist tr th.wish_count, table.cart.resulya thead tr th.cart_count').text('Количество');
$('table.cart.resulya th.cart_summ').text('Сумма заказа');
$('.cart .cart_last_trr .cart_summ, tr.wishlist_last_trr  td.wishlist_summ').attr('colSpan', 2);
if (!$(".cart_old_price").is(":visible"))
{
//$('.cart').find('.cart_last_trr .cart_totalr').attr('colSpan', 4);
$('.cart').find('.cart_delivery').attr('colSpan', 3);	
}
else
{
//$('.cart').find('.cart_last_trr .cart_totalr').attr('colSpan', 4);
$('.cart').find('.cart_delivery').attr('colSpan', 4);	
}
$('tr.wishlist_last_trr td.wishlist_totalr').attr('colSpan', 5); 
$('table.user_order').find('th.sum').text('Сумма');

} 

if(widt<=640)
{
$('table.user_order').find('.itog').attr('colSpan', 4);
$('.cart .cart_name, table.cart.resulya thead tr th.cart_name').attr('colSpan', 1);	
//$('.cart .cart_last_trr .cart_totalr, tr.wishlist_last_trr td.wishlist_totalr, table.cart.resulya tbody tr td.cart_delivery').attr('colSpan', 2);

//$('.cart .cart_last_trr .cart_summ, tr.wishlist_last_trr  td.wishlist_summ').attr('colSpan', 2);
$('.cart').find('.cart_delivery').attr('colSpan', 2);	
//$('table.cart.resulya .cart_last_trr .cart_totalr').attr('colSpan', 3);
}
if(widt>=640 && widt<=800)
{
$('table.user_order').find('.itog').attr('colSpan', 5);
//$('.cart .cart_name, table.cart.resulya thead tr th.cart_name').attr('colSpan', 2);	
//$('.cart .cart_last_trr .cart_totalr, tr.wishlist_last_trr td.wishlist_totalr, table.cart.resulya tbody tr td.cart_delivery').attr('colSpan', 2);
//$('.cart .cart_last_trr .cart_summ, tr.wishlist_last_trr  td.wishlist_summ').attr('colSpan', 2);
$('.cart').find('.cart_delivery').attr('colSpan', 2);	
$('tr.wishlist_last_trr td.wishlist_totalr').attr('colSpan', 4);	
//$('table.cart.resulya .cart_last_trr .cart_totalr').attr('colSpan', 3);
}	
}

$(window).trigger('resize'); */