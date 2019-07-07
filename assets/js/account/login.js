import "../../scss/account/login.scss";

function initTogglePassword(){
    const $button = $("#toggle_password");
    const $password_field = $("#password_input");

    $button.click(function(){
        $button.toggleClass("fa-eye fa-eye-slash");
        if($password_field.attr("type") == "password"){
            $password_field.attr("type", "text");
        }else{
            $password_field.attr("type", "password");
        }
    });
}

$(document).ready(function(){
    initTogglePassword();
});