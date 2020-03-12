
function initChangeShowName()
{
    const $startEdit = $("#user_showname_startedit");
    const $normal = $("#user_showname_normal");
    const $edit = $("#user_showname_edit");
    const $cancle = $("#user_showname_cancle");
    const $save = $("#user_showname_save");
    const $input = $("#user_showname_input");
    const $name = $("#user_showname_name");

    function show(){
        $normal.addClass("d-none");
        $edit.removeClass("d-none");
        $input.val($name.text());
    }

    function hide() {
        $edit.addClass("d-none");
        $normal.removeClass("d-none");
    }

    $startEdit.click(show);
    $cancle.click(hide);

    $save.click(function() {

        const value = $input.val();

        if(value == "")
            return;

        hide();

        $.ajax({
            url: "/account/action?show_name=" + value,
            success: () => $name.text(value),
            error: e => console.error(e)
        });
    });

}

function initResendVerifyEmail() {
    const $link = $("#user_verify_email");

    $link.click(function() {

        $link.addClass("d-none");

        $.ajax({
            url: "/account/action?verify_email",
            success: () => $link.removeClass("d-none"),
            error: e => console.error(e)
        });
    });
}

export function initAccount() {
    initChangeShowName();
    initResendVerifyEmail();
}