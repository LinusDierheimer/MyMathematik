import $ from "jquery";
import "bootstrap";

function initCockieInfo(cookieinfo){
    if(!document.cookie.includes("cookies=true"))
        cookieinfo.classList.add("cookiesvisible");
}

function initCookieButton(cookieinfo){
    $("#cookiesAccepted").click(function(){
        const livetime = $('#cookiesaccepted_livetime').val();
        document.cookie = "cookies=true; path=/; max-age=" + eval(livetime);
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
    const livetime = $('#language_livetime').val();
    document.cookie = "language=" + language + "; path=/; max-age=" + eval(livetime);
}

function initLanguage(){
    initLanguageCookie();
    initLanguageSwitcher();
}

$(document).ready(function(){
    initCookies();
    initLanguage();
    initScrollUp();
});