$(document).ready(function () {

    var widt = $(window).width();
    var scrollTopPrev = 0;

    /*fancybox*/
    $('[data-fancybox]').fancybox();

    /*прокрутка к форме загрузки сметы*/
    $(".js-btn-calc").click(function () {

        var isHomePage = window.location.pathname == '/' ? true : false;
        event.preventDefault();

        if(!isHomePage) {
            window.location.href = '/#form-smeta';
            return;
        }

        var elementClick = $(this).attr("href");
        var elementClickTop = $(elementClick).offset().top;

        var headerTopCont = $('#header-top');
        var headerTopContTop = 0;

        if($('div').is('.diafan-admin-link')) {
            headerTopContTop = 52;
        }
        
        console.log('Есть панель - ' + $('header').is('diafan-admin-panel'));

        var destination = elementClickTop - headerTopContTop - headerTopCont.innerHeight();

        scrollTo(destination, 800);

        return;
    });

    /*прокрутка к работнику (страница - О компании)*/
    $("a[worker-id]").click(function (event) {
        event.preventDefault();

        var workerId = $(this).attr('worker-id'),
            destination = $('#workers-item-' + workerId).offset().top - 60;

        scrollTo(destination, 800);

        return false;
    });

    /*Прокрутка к элементу*/
    $("a.scrollto").click(function () {
        var elementClick = $(this).attr("href");
        var destination = $(elementClick).offset().top;
        scrollTo(destination, 800);
        return false;
    });

    /*непосредственно функция прокрутки*/
    function scrollTo(destination, speed) {
        jQuery("html:not(:animated),body:not(:animated)").animate({scrollTop: destination}, speed);
    }

    /*скрываем / показываем header при прокрутке*/
    function toggleHeaderTop() {
        var scrollTopCurrent = $(window).scrollTop();
        var toggleClass = "top-hidden";
        var headerTopCont = $('#header-top');
        var toggleDist = 170;

        if (scrollTopCurrent > scrollTopPrev && scrollTopCurrent > toggleDist) {
            headerTopCont.addClass(toggleClass);
        }
        else if((headerTopCont.hasClass(toggleClass)
            && scrollTopCurrent < scrollTopPrev) || scrollTopCurrent < toggleDist) {
            headerTopCont.removeClass(toggleClass);
        }
        scrollTopPrev = scrollTopCurrent;
    }

    /*открываем формы*/
    $(document).on('click', 'a.js-form-link', function () {
        var _this = $(this);
        var form = $('#' + _this.attr('data-target-form'));

        $("div#overlay").fadeIn('slow');
        form.fadeIn('slow');
        return false;
    });

    $(document).on('click', '.close,div#overlay', function () {
        $("div#overlay").fadeOut('slow');
        $(".modal:visible").fadeOut('slow');
        return false;
    });

    /*слайдер товаров на главной*/
    $('.js-shop-slider').slick({
        slidesToShow: 5,
        slidesToScroll: 1,
        arrows: true,
        dots: false,
        fade: false,
        autoplay: true,
        autoplaySpeed: 3000,
        responsive: [
            {
                breakpoint: 1540,
                settings: {
                    slidesToShow: 4,
                    slidesToScroll: 4
                }
            },
            {
                breakpoint: 1190,
                settings: {
                    slidesToShow: 3,
                    slidesToScroll: 3
                }
            },
            {
                breakpoint: 880,
                settings: {
                    slidesToShow: 2,
                    slidesToScroll: 2
                }
            },
            {
                breakpoint: 700,
                settings: {
                    slidesToShow: 1,
                    slidesToScroll: 1
                }
            }]
    });

    /*слайдер товаров на главной*/
    $('.footer__slider .bs_block').slick({
        slidesToShow: 8,
        slidesToScroll: 1,
        arrows: true,
        dots: true,
        fade: false,
        adaptiveHeight: false,
        autoplay: true,
        autoplaySpeed: 3000,
        responsive: [
            {
                breakpoint: 1540,
                settings: {
                    slidesToShow: 6,
                    slidesToScroll: 4
                }
            },
            {
                breakpoint: 1190,
                settings: {
                    slidesToShow: 4,
                    slidesToScroll: 3
                }
            },
            {
                breakpoint: 880,
                settings: {
                    slidesToShow: 3 ,
                    slidesToScroll: 2
                }
            },
            {
                breakpoint: 700,
                settings: {
                    slidesToShow: 2,
                    slidesToScroll: 1
                }
            }]
    });

    /*слайдер изображений проектов*/
    $('.project-slider').each(function(){
        /*слайдер товаров на главной*/
        $(this).slick({
            slidesToShow: 1,
            slidesToScroll: 1,
            arrows: false,
            dots: true,
            fade: true,
            autoplay: true,
            autoplaySpeed: 3000
        });
    });

    //слайдер проектов на странице проектов
    $('.project-list-slider').slick({
        slidesToShow: 4,
        slidesToScroll: 1,
        infinite: false,
        arrows: false,
        dots: true,
        fade: false,
        adaptiveHeight: false,
        autoplay: false,
        autoplaySpeed: 3000,
        responsive: [
            {
                breakpoint: 1190,
                settings: {
                    slidesToShow: 5,
                    slidesToScroll: 3
                }
            },
            {
                breakpoint: 880,
                settings: {
                    slidesToShow: 3,
                    slidesToScroll: 2
                }
            },
            {
                breakpoint: 700,
                settings: {
                    slidesToShow: 1,
                    slidesToScroll: 1
                }
            }]
    });
    
    function setMargin() {

        if($(window).width() >= 1024) {
            $('.header__menu_cat > ul > li').each(function () {
                var _this = $(this),
                    curPosition = _this.position().top;
                _this.find('ul').css('padding-top', curPosition + 'px');
            });
        }
        else {
            $('.header__menu_cat > ul > li').each(function () {
                var _this = $(this);

                _this.find('ul').css('padding-top', '');
            });
        }
        
    }

    // палец
    $('.wrap-finger').scroll(function () {
        ($(this).scrollLeft() > 1) ? $('.finger').fadeOut(): $('.finger').fadeOut();
    });

    function showFinger () {
        $('.finger').each(function () {
            var _this = $(this),
                wrapFinger = _this.closest('.wrap-finger'),
                wrapFingerWidth = wrapFinger.width(),
                tableWidth = wrapFinger.find('table').width();
            if(wrapFingerWidth < tableWidth) {
                _this.show(500);
            }
            else {
                _this.hide();
            }
        });
    }

    (function ($) {
        /*события при загрузке страницы*/
        $(window).on("load", function () {
            $('.responsive').fadeTo("slow", 1);
            setMargin();
            showFinger();
        });

        /*события при скролле*/
        $(window).on("scroll", function () {
            toggleHeaderTop();
        });
    })(jQuery);

    /*Открываем меню пользователя в шапке*/
    $('.header__user-link').click(function () {
        var toggleClass = 'open';
        $(this).toggleClass(toggleClass);
        $(this).next('.header__user-login').toggleClass(toggleClass);
    });

    var fl = 0;
    /*присваиваем <body> padding-bottom = footer.height*/
    var footer_height = $('footer').innerHeight();
    $('body').css("padding-bottom", footer_height);


    $(window).resize(function () {
        showFinger();
        /*меняем внутренний отступ снизу у <body> чтоб влез разожравшийся <footer>*/
        footer_height = $('footer').innerHeight();
        $('body').css("padding-bottom", footer_height);

        widt = $(window).width();

        /*if (widt <= 668) {
            $(document).on('click', '.header__menu_cat li.parent a', function (event) {
                if ($(this).hasClass('is-active'))
                    $(this).removeClass('is-active');
                else {
                    $(this).addClass('is-active');
                    event.preventDefault();
                }
            });

            $('.sty.hide').addClass('btn');

            $(document).on('click', '.sty.hide', function (event) {
                if ($(this).hasClass('is-active')) {
                    $(this).removeClass('is-active');
                    $(this).next().slideUp('slow');
                }
                else {
                    $(this).addClass('is-active');
                    $(this).next().slideDown('slow');
                }

            });
        }
        else $('.sty.hide').removeClass('btn');*/

        if (widt > 1540) {
            $('.responsive.slick-initialized').slick('unslick');
            fl = 1;
        }
        else if (widt <= 1540) {
            fl = 0;
            $('.responsive').not('.slick-initialized').slick({
                slidesToShow: 4,
                slidesToScroll: 4,
                adaptiveHeight: true,
                responsive: [
                    {
                        breakpoint: 1541,
                        settings: "unslick"
                    },
                    {
                        breakpoint: 1540,
                        settings: {
                            slidesToShow: 4,
                            slidesToScroll: 4,
                        }
                    },
                    {
                        breakpoint: 1190,
                        settings: {
                            slidesToShow: 3,
                            slidesToScroll: 3
                        }
                    },
                    {
                        breakpoint: 880,
                        settings: {
                            slidesToShow: 2,
                            slidesToScroll: 2
                        }
                    },
                    {
                        breakpoint: 700,
                        settings: {
                            slidesToShow: 1,
                            slidesToScroll: 1
                        }
                    }]
            });
        }


    });


    /*if (widt > 1540) {
        $('.responsive.slick-initialized').slick('unslick');
        fl = 1;
    }
    else if (widt <= 1540) {
        fl = 0;
        $('.responsive').slick({
            slidesToShow: 4,
            slidesToScroll: 4,
            adaptiveHeight: true,
            responsive: [
                {
                    breakpoint: 1541,
                    settings: 'unslick'
                },
                {
                    breakpoint: 1540,
                    settings: {
                        slidesToShow: 4,
                        slidesToScroll: 4,
                    }
                },
                {
                    breakpoint: 1190,
                    settings: {
                        slidesToShow: 3,
                        slidesToScroll: 3
                    }
                },
                {
                    breakpoint: 880,
                    settings: {
                        slidesToShow: 2,
                        slidesToScroll: 2
                    }
                },
                {
                    breakpoint: 700,
                    settings: {
                        slidesToShow: 1,
                        slidesToScroll: 1
                    }
                }]
        });
    }*/


    $(window).scroll(function () {
        var h = $(window).scrollTop();
        if (h < 1) {
            $("body").removeClass("fixed")
        }
        else {
            $("body").addClass("fixed")
        }

    });

    $('.main-slider-wrap .slider').slick({
        dots: true,
        arrows: true,
        infinite: true,
        speed: 1200,
        slidesToShow: 1,
        autoplay: true,
        fade: true,
        cssEase: 'linear',
        autoplaySpeed: 5000
        // adaptiveHeight: true,
    });

    $('.shop_all_img__slider').slick({
        slidesToShow: 1,
        slidesToScroll: 1,
        arrows: false,
        fade: true,
        asNavFor: '.shop_all_img__nav'
    });

    $('.shop_all_img__nav').slick({
        slidesToShow: 3,
        slidesToScroll: 1,
        asNavFor: '.shop_all_img__slider',
        dots: false,
        focusOnSelect: true
    });

    //$('.header__left').mCustomScrollbar();

    if (widt <= 1024) {
        /*$(document).on('click', '.header__menu_cat li.parent a', function (event) {
            if ($(this).hasClass('is-active'))
                $(this).removeClass('is-active');
            else {
                $(this).addClass('is-active');
                event.preventDefault();
            }
        });*/

        $('a#js-open-mob-menu').click(function () {
            event.preventDefault();
            $('.header__left').addClass('is-active');
            $('nav.header__top_menu-nav .c-hamburger').click();
        });

        $('.header__left-close').click(function () {
            event.preventDefault();
            $('.header__left').removeClass('is-active');
            $('ul.menu.is-active').removeClass('is-active');
            $('.btn-back.is-active').removeClass('is-active');
        });
    }

    if (widt <= 668) {
        // $(document).on('click', '.header__menu_cat li.parent a', function(event) {
        //     if($(this).hasClass('is-active'))
        //        $(this).removeClass('is-active');
        //     else {
        //         $(this).addClass('is-active');
        //         event.preventDefault();
        //     }
        // });

        $('.sty.hide').addClass('btn');

        $(document).on('click', '.sty.hide', function (event) {
            $(this).next().slideToggle('fast');
        });
    }
    else $('.sty.hide').removeClass('btn');
    
    /*function changeMenuLvl (way) {
        var curMenu = $('ul.menu.is-active');
        var curLvl = Number(curMenu.attr('data-menu-lvl'));
        var nextLvl;
        var activeClass = 'is-active';
        
        console.log(way);

        if(curLvl > 0) {
            if (way == 'up') {
                nextLvl = curLvl + 1;
            }
            else if (way == 'down') {
                nextLvl = curLvl - 1;
            }
            else {
                console.log('Ошибка! Неизвестная команда.');
            }
            
            console.log('nextLvl: ' + nextLvl);

            curMenu.removeClass(activeClass);
            curMenu.find('ul.menu-lvl-' + nextLvl).addClass(activeClass);
            toggleBackBtn (nextLvl);
        }
        else {
            console.log('Ошибка! Не определён текущий уровень меню.');
        }
    }*/

    function toggleBackBtn (curLvl) {
        var backBtn = $('.btn-back');
        if(curLvl > 1) {
            backBtn.addClass('is-active');
        }
        else {
            backBtn.removeClass('is-active');
        }
    }

    //мобильное меню
    $(document).on('click', '.next-lvl-btn', function (event) {
        var activeClass = "is-active";
        var _this = $(this);
        var curLvl = Number(_this.closest('ul').attr('data-menu-lvl')) + 1;
        
        console.log(curLvl);

        toggleBackBtn(curLvl);

        $('.menu.is-active').removeClass(activeClass);

        _this.closest('li').find('> ul').addClass(activeClass);
    });

    $(document).on('click', '.btn-back', function (event) {
        var curMenu = $('.menu.is-active');
        var curLvl = Number(curMenu.attr('data-menu-lvl')) - 1;
        var activeClass = "is-active";

        curMenu.removeClass(activeClass);
        curMenu.closest('ul.menu-lvl-' + curLvl).addClass(activeClass);

        toggleBackBtn(curLvl);
    });

    (function () {

        "use strict";

        var toggles = document.querySelectorAll(".c-hamburger");

        for (var i = toggles.length - 1; i >= 0; i--) {
            var toggle = toggles[i];
            toggleHandler(toggle);
        }

        function toggleHandler(toggle) {
            toggle.addEventListener("click", function (e) {
                e.preventDefault();
                (this.classList.contains("is-active") === true) ? this.classList.remove("is-active") : this.classList.add("is-active");
                /* if(widt<=668 && $(this).hasClass('c_hamburger_bot'))
               {
                   if(!$(this).next().is(':visible')) $(this).next().css({'display': 'block'});
                   $(this).next().toggleClass('slow');
               }
               else
               {
                    */
                $(this).next().slideToggle();
                //}
            });
        }
    })();


    res(widt);

    diafan_ajax.success['cart_recalc'] = function (form, response) {
        widt = $(window).width();
        res(widt);
    };

    $(window).resize(function () {
        widt = $(window).width();
        res(widt);
    });

    $(document).on('click', '.minus', function () {
        var $input = $(this).parent().find('input');
        var count = parseInt($input.val()) - 1;
        count = count < 1 ? 1 : count;
        $input.val(count);
        $input.change();
        return false;
    });

    $(document).on('click', '.plus', function () {
        var $input = $(this).parent().find('input');
        $input.val(parseInt($input.val()) + 1);
        $input.change();
        return false;
    });

    $(document).on('click', 'a.feed', function () {
        $("div#overlay").fadeIn('slow');
        $(".mod_1").css("height", "auto");
        var heigh = $(".mod_1").innerHeight() + 2;
        $(".mod_1").fadeIn('slow').css({
            "height": heigh
        });
        return false;
    });

});


function res(widt) {
    //купон
    if (widt >= 865) {
        var cord = $('.cart_table .cart_last_trr').offset();
        $('.coupon_block').offset(cord);
    }
    //корзина
    if (widt <= 800) {
        $('.cart th.cart_name, table.wishlist tr th.wish_name').text('Наим-ие');
        $('.cart th.cart_count, table.wishlist tr th.wish_count, table.cart.resulya thead tr th.cart_count').text('Кол-во');
        $('table.cart.resulya th.cart_summ').text('Сумма');
        $('.cart').find('.cart_delivery').attr('colSpan', 2);
        $('table.user_order').find('th.sum').text('Сумма');
        $('td.cart_additional').attr('colSpan', 2);
        $('cart_totalr__bl')
    }
    else {

        $('.cart th.cart_name, table.wishlist tr th.wish_name').text('Наименование товара');
        $('.cart th.cart_count, table.wishlist tr th.wish_count, table.cart.resulya thead tr th.cart_count').text('Количество');
        $('table.cart.resulya th.cart_summ').text('Сумма заказа');
        if (!$(".cart_old_price").is(":visible")) {
            $('td.cart_additional').attr('colSpan', 3);
            $('table.cart.resulya td.cart_additional').attr('colSpan', 2);
            $('.cart').find('.cart_delivery').attr('colSpan', 3);
        }
        else {
            $('td.cart_additional').attr('colSpan', 4);
            $('table.cart.resulya td.cart_additional').attr('colSpan', 3);
            $('.cart').find('.cart_delivery').attr('colSpan', 4);
        }
    }

}
