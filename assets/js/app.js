import $ from "jquery";
import "bootstrap";

import "../scss/app.scss";

function getDesign(){
    return $("#design").val();
}

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

function updateAccordion(){
    const chapterbuttons = document.getElementsByClassName("chapterheader");
    for(var i = 0; i < chapterbuttons.length; i++){
        const header = chapterbuttons[i];
        const isActive = header.classList.contains("active");
        const panel = header.nextElementSibling;

        if(isActive){
            panel.style.maxHeight = panel.scrollHeight + "px";
            panel.style.paddingTop = "10px";
        }else{
            panel.style.maxHeight = null;
            panel.style.paddingTop = "0";
        }

    }
}

function initAccordion(){
    const chapterbuttons = document.getElementsByClassName("chapterheader");
    
    for(var i = 0; i < chapterbuttons.length; i++){
        chapterbuttons[i].addEventListener('click', function(){

            const isActive = this.classList.contains("active");

            const panel = this.nextElementSibling;
            const image = this.children[1];
            if(isActive){
                panel.style.display = "none";
                panel.style.paddingTop = "0";
            }else{
                panel.style.display = "block";
                panel.style.paddingTop = "10px";
            }

            image.classList.toggle("active");
            this.classList.toggle("active");

            updateAccordion();

        });
    }
}

function initScrollToContent(){
    $('#scrollToContentButton').click(function(){
        $('html, body').animate(
            { scrollTop: $('#content').offset().top - $('#header').height() }, 
        'slow');
        console.log("Hallo");
    });
}

function initAdminVideoUploadOptions(){

    const $this = $(this);
    const $input = $this.find('.videoname');
    const originValue = $input.val();
    const $renameOptions = $this.find(".nameoptions");
    const $videoOptions = $this.find(".videooptions");

    $input.on('input', function(){
        if($input.val() != originValue)
            $renameOptions.css("display", "inline-block");
        else
            $renameOptions.css("display", "none");
    });

    $renameOptions.find(".discardrename").click(function(){
        $renameOptions.css("display", "none");
        $input.val(originValue);
    });

    $renameOptions.find(".dorename").click(function(){
        $renameOptions.css("display", "none");
        $.ajax("/admin/doaction", {
            data: {
                action: "rename",
                from: $(this).data("file"),
                to: $input.val(),
            },
            success: function(data){
            },
            error: function(error){
                console.error(error);
                $input.val(originValue);
            }
        });
    });

    $videoOptions.find(".delete").click(function(){
        if(!confirm("Do you really want to delete the video? It will be deleted from disk. Consider removing it just from the config file."))
            return;
        
        $.ajax("/admin/doaction", {
            data: {
                action: "delete",
                file: $(this).data("file")
            },
            success: function(data){
                $this.remove();
            },
            error: function(error){
                console.error(error);
            }
        });
    });
}

function initAllAdminVideoUploadOptions(){
    $(".videofile").each(initAdminVideoUploadOptions);
}

function initResetAdminVideoConfig(){
    const $editor = $(".editor");
    const originContent = $editor.val();

    $("#resetConfig").click(function(){
        $editor.val(originContent);
    })
}

function initLoginTogglePassword(){
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

function initRegisterTogglePassword(){
    const $button = $("#toggle_password");
    const $password_field = $("#password_input");
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

function initMenuBar(){

    if(getDesign() === "simple")
        return;

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
    initCookies();
    initLanguage();
    initScrollUp();
    initAccordion();
    initScrollToContent();
    initAllAdminVideoUploadOptions();
    initResetAdminVideoConfig();
    initLoginTogglePassword();
    initRegisterTogglePassword();
    initMenuBar();
});