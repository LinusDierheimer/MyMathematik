import "../../scss/home/home.scss";

function initScrollToContent(){
    $('#scrollToContentButton').click(function(){
        $('html, body').animate(
            { scrollTop: $('#content').offset().top - $('#header').height() }, 
        'slow');
        console.log("Hallo");
    });
}

$(document).ready(function(){
    initScrollToContent();
})