function initRegisterTogglePassword(){
    const $button = $("#register_toggle_password");
    const $password_field = $("#register_password_input");
    const $repeat_password_form = $("#password_repeat_form");
    const $repeat_password_field = $("#password_repeat_input");
    const $register_config = $("#single_password_field");

    $button.click(function(){
        $button.toggleClass("fa-eye fa-eye-slash");
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
    });
}

export function initRegister() {
    initRegisterTogglePassword();
}