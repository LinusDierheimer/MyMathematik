import "../../scss/account/register.scss";

function initTogglePassword(){
    const $button = $("#toggle_password");
    const $password_field = $("#password_input");
    const $repeat_password_form = $("#password_repeat_form");
    const $repeat_password_field = $("#password_repeat_input");
    const $register_config = $("#single_password_field");

    $button.click(function(){
        $(this).toggleClass("fa-eye fa-eye-slash");
        if($password_field.attr("type") == "password"){
            $password_field.attr("type", "text");
            $repeat_password_form.css("display", "none");
            $repeat_password_field.val("dummy");
            $register_config.val("true");
        }else{
            $password_field.attr("type", "password");
            $repeat_password_form.css("display", "block");
            $repeat_password_field.val($password_field.val());
            $register_config.val("false");
        }
    })
}

$(document).ready(function(){
    initTogglePassword();
});