function initLoginTogglePassword() {
    const $button = $("#login_toggle_password");
    const $password_field = $("#login_password_input");

    $button.click(function(){
        $button.toggleClass("fa-eye fa-eye-slash");
        if($password_field.attr("type") == "password"){
            $password_field.attr("type", "text");
        }else{
            $password_field.attr("type", "password");
        }
    });
}

export function initLogin() {
    initLoginTogglePassword();
}