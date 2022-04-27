"use strict";

document.addEventListener("DOMContentLoaded", function () {
   
    // mob nav open/close
    $('.close-mob-nav, .overlay,.menu a.slowscroll').click(function (){
        $('.wrap-mob-nav, .overlay').removeClass('open_panel');
        $(this).parents('body').removeClass('body_panel');
        $(this).parents('html').removeClass('body_panel');
    });
    $('.open-mob-nav').click(function (e){
        $('.wrap-mob-nav, .overlay').toggleClass('open_panel');
        e.preventDefault();
        $(this).parents('body').toggleClass('body_panel');
        $(this).parents('html').toggleClass('body_panel');
    });

    // slider
    var swiper = new Swiper('.swiper-container-projects', {
        navigation: {
            nextEl: '.swiper-button-next-projects',
            prevEl: '.swiper-button-prev-projects'
        },
        slidesPerView: 3,
        spaceBetween: 15,

        breakpoints: {
            1199: {
                slidesPerView: 2,
            },
            735: {
                slidesPerView: 1,
            }
        },
        loop: true
    });

    //scroll to block
    $(".wrapper").on("click","a.slowscroll", function (event) {
        //отменяем стандартную обработку нажатия по ссылке
        event.preventDefault();

        //забираем идентификатор бока с атрибута href
        var id  = $(this).attr('href'),

            //узнаем высоту от начала страницы до блока на который ссылается якорь
            top = $(id).offset().top;

        //анимируем переход на расстояние - top за 1000 мс
        $('body,html').animate({scrollTop: top}, 1000);
    });
	
	$('<style>'+
	    '.scrollTop{ display:none; z-index:9999; position:fixed;'+
	    'bottom:20px; left:90%; width: 48px;height: 55px;'+
	    'background: url("/up-arrow.png"); background-repeat: no-repeat;background-size: contain;}' +
	    '.scrollTop:hover{ background-position:0 -5px;}'
	+'</style>').appendTo('body');
	var speed = 550,
	    $scrollTop = $('<a href="#" class="scrollTop">').appendTo('body');		
	    $scrollTop.click(function(e){
	        e.preventDefault();
	$( 'html:not(:animated),body:not(:animated)' ).animate({ scrollTop: 0}, speed );
	});
	
	//появление
	function show_scrollTop(){
	( $(window).scrollTop() > 330 ) ? $scrollTop.fadeIn(700) : $scrollTop.fadeOut(700);
	}
	$(window).scroll( function(){ show_scrollTop(); } );
	show_scrollTop();


    $('[data-toggle="tooltip"]').tooltip();


    // Открытие мобильного меню
    $("#menu-burger").click(function () {
        $('body').css('overflow', 'hidden');
        $(this).toggleClass('open');
        $(".header_mobile_menu").animate({width: 'toggle'}, 350);
        $(".close_bg").animate({width: 'toggle'}, 350);
        
    });
    $(".close_bg").click(function () {
        $('body').css('overflow', 'auto');
        $('#menu-burger').removeClass('open');
        $(".header_mobile_menu").animate({width: 'toggle'}, 350);
        $(".close_bg").animate({width: 'toggle'}, 350);
    });


    // Кнопка перемещения к форме для заполнения (на страницу Стать спикером)
    // получаем кнопку и заголовок формы
    const toForm = document.querySelectorAll('.js-btn'),
          form   = document.getElementById('js-to-form');

    toForm.forEach(item => {
        item.addEventListener('click', function() {
            form.scrollIntoView({block: "center", behavior: "smooth"});
        });
    });

});

// Модальное окно
document.addEventListener("DOMContentLoaded", function () {
    // Модальное окно будет открываться при клике на элемент с классом popup-link'
    const popuplinks = document.querySelectorAll('.popup-link');
    // Получаем объект body
    const body = document.querySelector('body');
    // 
    const lockPadding = document.querySelectorAll('.lock__padding');
    const popupCloseIcon = document.querySelectorAll('.close-popup');
    let unlock = true;
    const timeout = 500;


    addEventListener('click', (el) => {
        if (el.target.closest('.popup-link')) {
            const popup = document.querySelector('#popup');
            popupOpen(popup);
        }
    })
    
    // if(popuplinks.length > 0) {
    //     for(let i = 0; i < popuplinks.length; i++) {
    //         const popupLink = popuplinks[i];
    //         popupLink.addEventListener('click', (e)=>{
    //             // const popupName = popupLink.getAttribute('');
    //             const popup = document.querySelector('#popup');
    //             popupOpen(popup);
    //         })
    //     }
    // }

    if(popupCloseIcon.length > 0) {
        for(let i = 0; i < popupCloseIcon.length; i++) {
            const el = popupCloseIcon[i];
            el.addEventListener('click', (e) => {
                popupClose(el.closest('.popup'));
            });
        }
    }

    function popupOpen(currentPopup) {
        if (currentPopup && unlock) {
            const popupActive = document.querySelector('.popup .open');
            if(popupActive) {
                popupClose(popupActive, false);
            } else {
                bodyLock();
            }
        }
        currentPopup.classList.add('open');
        currentPopup.addEventListener('click', (e) =>{
            if(!e.target.closest('.popup-show')) {
                popupClose(e.target.closest('.popup'));
            }
        });
    }

    function popupClose(popupActive, duUnlock = true) {
        if(unlock) {
            popupActive.classList.remove('open');
            if (duUnlock) {
                bodyUnlock();
            }
        }
    }

    function bodyLock() {
        const lockPaddingValue = window.innerWidth - document.querySelector('.wrapper').offsetWidth + 'px';
        if(lockPadding > 0) {
            for(let i = 0; i < lockPadding.length; i++) {
                const el = lockPadding[i];
                el.style.paddingRight = lockPaddingValue;
            }
        }
        
        body.style.paddingRight = lockPaddingValue;
        body.classList.add('lock');

        unlock = false;
        setTimeout(function () {
            unlock = true;
        }, timeout);
    }

    function bodyUnlock () {
        setTimeout(function () {
            for(let i = 0; i < lockPadding.length; i++) {
                const el = lockPadding[i];
                el.style.paddingRight = '0px';
            }
            body.style.paddingRight = '0px';
            body.classList.remove('lock');
        }, timeout);

        unlock = false;
        setTimeout(function () {
            unlock = true;
        }, timeout);
    }

});

// Стрнаница поста новости. Сохранение категории новости в localStorage, чтобы при переходе по ссылке 
// передать значение в раздел списка новостей

document.addEventListener("DOMContentLoaded", function () {
    const category = document.querySelector('#news-get-category');

    if (category) {
        category.addEventListener('click', item => {
            item.preventDefault();
    
            // сохраняем в localStorage, на странице новостей, подхватим эту переменную 
            localStorage.setItem('news-categoty', category.textContent.trim());
    
            // редирект на страницу новостей
            window.location.href = '/news';
        })
    }
});