import "../scss/app-home.scss";
import "./app.js";

function initMenuBar(){

    const $header = $('#header');

    function updateMenuBar(){
        if($(window).scrollTop() < 150 )
            $header.addClass('header-transparent');
        else
            $header.removeClass('header-transparent');
    }

    $(document).scroll(updateMenuBar);
    updateMenuBar(); //When page is reloaded, the scroll is saved in modern browsers. So have to check at init;
}

$(document).ready(function(){
    initMenuBar();
});