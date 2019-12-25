function initScrollToContent(){
    $('#scrollToContentButton').click(function(){
        $('html, body').animate({
            scrollTop: $('#content').offset().top - $('#header').height()
        }, 'slow');
    });
}

export function initHome() {
    initScrollToContent();
}