import "../scss/app.scss";

import $ from "jquery";
import "bootstrap";

function initMenuBar(){

    const $header = $('#header');

    function updateMenuBar(){
        if($(window).scrollTop() < 50 )
            $header.addClass('header-transparent');
        else
            $header.removeClass('header-transparent');
    }

    $(document).scroll(updateMenuBar);
    updateMenuBar(); //When page is reloaded, the scroll is saved in modern browsers. So have to check at init;
}

function initCockieInfo(cookieinfo){
    if(!document.cookie.includes("cookies=true"))
        cookieinfo.classList.add("cookiesvisible");
}

function initCookieButton(cookieinfo){
    $("#cookiesAccepted").click(function(){
        document.cookie = "cookies=true; path=/";
        cookieinfo.classList.remove("cookiesvisible");
    })
}

function initCookies(){
    const cookieinfo = document.getElementById("cookies"); 
    initCockieInfo(cookieinfo);
    initCookieButton(cookieinfo);
}

function initScrollUp(){
    $("#scrollUp").click(function(){
        $('html, body').animate({ scrollTop: 0 }, 'slow');
    });
}

function initLanguageSwitcher(){
    $(".languageswitcher").click(function(){
        document.cookie = "language=" + $(this).data("language") + "; path=/";
        location.reload();
    });
}

function initLanguageCookie(){
    const language = $('#language_code').val();
    document.cookie = "language=" + language + "; path=/";
}

function initLanguage(){
    initLanguageCookie();
    initLanguageSwitcher();
}

$(document).ready(function(){
    initCookies();
    initLanguage();
    initScrollUp();
    initMenuBar();
});