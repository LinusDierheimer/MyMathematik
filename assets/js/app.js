import "jquery";
import "bootstrap";

import "../scss/app.scss";

function getCookie(cname) {
    var name = cname + "=";
    var decodedCookie = decodeURIComponent(document.cookie);
    var ca = decodedCookie.split(';');
    for(var i = 0; i <ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0) == ' ') {
            c = c.substring(1);
        }
        if (c.indexOf(name) == 0) {
            return c.substring(name.length, c.length);
        }
    }
    return "";
}

function removeAllClassesStartingWith(element, start){
    const $elem = $(element);
    if($elem.length == 0)
        return;
    const classes = $elem[0].className.split(" ").filter(c => !c.startsWith(start));
    $elem[0].className = classes.join(" ").trim();
    return $elem[0];
}

function getCookieLivetime() {
    return eval(
        $("#cookie_livetime").val()
    );
}

function setCookie(name, value, path = '/', livetime = getCookieLivetime()){
    document.cookie = "" + name + "=" + value + "; path=" + path + "; max-age=" + livetime;
}

function initMenuBar(){

    if($("#header_mode").val() === "simple")
        return;

    const $header = $('#header');

    const updateMenuBar = function(){
        if($(window).scrollTop() < 150 )
            $header.addClass('header-transparent');
        else
            $header.removeClass('header-transparent');
    }

    $(document).scroll(updateMenuBar);
    updateMenuBar(); //When page is reloaded, the scroll is saved in modern browsers. So have to check at init;

    $(".switch-header").click(function(){
        const body = $(this).next(".switch-content");
        if(body.height() == 0)
            body.animate({height:body[0].scrollHeight},200);
        else
            body.animate({height:0}, 200);
    })
}

function initCookies(){

    const $cookieinfo = $("#cookieinfo");

    if(!document.cookie.includes("cookies=true"))
        $cookieinfo.addClass("cookiesvisible");

    $("#cookiesAccepted").click(function(){
        setCookie("cookies", "true");
        $cookieinfo.removeClass("cookiesvisible");
    })

}

function initDesign(){

    var prev_design = $("#initial_design").val();

    const setDesign = function(design){

        $("#design-check-circle-" + prev_design)
            .removeClass("fa-check-circle")
            .addClass("fa-circle");

        $("#design-check-circle-" + design)
            .removeClass("fa-circle")
            .addClass("fa-check-circle");

        setCookie("design", design);

        prev_design = design; //set it already now, so system will stay system

        if(design == "" || design == "system"){
            if(window.matchMedia("(prefers-color-scheme: dark)").matches)
                design = "design-dark";
            else
                design = "design-white";
        }

        removeAllClassesStartingWith("body", "design-");
        $("body").addClass(design);
    }

    setDesign(prev_design); //inital design set

    const updateWhenSystemDesign = function(e) {
        if(prev_design == "system")
            setDesign("system");
    }

    window.matchMedia("(prefers-color-scheme: dark)").addListener(updateWhenSystemDesign);
    window.matchMedia("(prefers-color-scheme: light)").addListener(updateWhenSystemDesign);

    $(".designswitcher").click(function(){
        setDesign($(this).data("css"));
    });
}

function initScrollUp(){
    $("#scrollUp").click(function(){
        $('html, body').animate({ scrollTop: 0 }, 'slow');
    });
}

function initLanguage() {

    const language = $('#language_code').val();
    setCookie("language", language);  
    
    $(".languageswitcher").click(function(){
        setCookie("language", $(this).data("languagecode"));
        location.reload();
    });
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
        });
    }
}

function initScrollToContent(){
    $('#scrollToContentButton').click(function(){
        $('html, body').animate({
            scrollTop: $('#content').offset().top - $('#header').height()
        }, 'slow');
    });
}

function initAdminVideo(){

    const $editor = $(".editor");
    const originContent = $editor.val();

    $("#resetConfig").click(function(){
        $editor.val(originContent);
    })

    $(".videofile").each(function(){
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
    });
}

function initLogin() {
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

function initRegister(){
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

$(document).ready(function(){
    initMenuBar();
    initCookies();
    initDesign();
    initScrollUp();
    initLanguage();
    initAccordion();
    initScrollToContent();
    initAdminVideo();
    initLogin();
    initRegister();
});