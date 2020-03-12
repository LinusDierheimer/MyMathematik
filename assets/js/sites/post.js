function initCreateAnswer(){
    const $create = $("#createanswer");
    const $buttons = $(".createanswerbutton");
    const $cancle = $(".canclecreatepost");

    $buttons.click(function() {
        $buttons.css('display', 'none');
        $create.removeClass('d-none');
        $create.addClass('d-inline-block');
    });

    $cancle.click(function() {
        $buttons.css("display", "inline-block");
        $create.removeClass('d-inline-block');
        $create.addClass('d-none');
    })
}

export function initPost() {
    initCreateAnswer();
}