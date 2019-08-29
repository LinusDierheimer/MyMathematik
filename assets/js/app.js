import "jquery";
import "bootstrap";

import "../scss/app.scss";

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

    $(".switch-header").click(function(){
        const body = $(this).next(".switch-content");
        if(body.height() == 0)
            body.animate({height:body[0].scrollHeight},200);
        else
            body.animate({height:0}, 200);
    });

    if($("#header_mode").val() !== "simple")
    {
        const $header = $('#header');

        const updateMenuBar = function(){
            if($(window).scrollTop() < 150 )
                $header.addClass('header-transparent');
            else
                $header.removeClass('header-transparent');
        }
    
        $(document).scroll(updateMenuBar);
        updateMenuBar(); //When page is reloaded, the scroll is saved in modern browsers. So have to check at init;    
    }
}

function initBody(){
    const $fullContent = $("#fullcontent");
    const minHeight = window.innerHeight - $("#footer").height();
    if($fullContent.height() < minHeight)
        $fullContent.height(minHeight);
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

    const language = $('#current_language_code').val();
    setCookie("language", language);  
    
    $(".languageswitcher").click(function(){
        setCookie("language", $(this).data("languagecode"));
        location.reload();
    });
}

function initVideoList(){
    $(".chaptertitle").click(function(){
        const $this = $(this);
        const $content = $this.next(".chaptercontent");
        if($content.height() == 0){
            $content.animate({height:$content[0].scrollHeight},200);
        }else{
            $content.animate({height:0}, 200);
        }
        $this.find(".dropdownbutton").toggleClass("dropdownbutton-active");
    })   
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

function initCalculator() {
    const $output = $("#calculatorOutput");
    const $calculation = $(".calculation");
    const $buttons = $(".calculatorButton");
    const $equalButton = $(".equalButton");
    const $acButton = $(".acButton");
    const $cButton = $(".cButton");

    $buttons.click(function(){
        $output.val($output.val() + $(this).text());
    })

    $equalButton.click(function(){
        $calculation.text($output.val());
        var raw = $output.val()
            .replace("/", " / ")
            .replace("*", " * ")
            .replace("-", " - ")
            .replace("+", " + ")
            .replace("π", " Math.PI ")
            .replace("e", " Math.E ");
        
        var res;
        try {
            res = eval(raw);
        } catch (error) {
            res = error.message;
            console.error(error);
        }

        $output.val(res);
    });

    $acButton.click(function(){
        $output.val("");
    });

    $cButton.click(function(){
        const val = $output.val();
        $output.val(val.substring(0, val.length - 1));
    });
}

$(document).ready(function(){
    initMenuBar();
    initBody();
    initCookies();
    initDesign();
    initScrollUp();
    initLanguage();
    initVideoList();
    initScrollToContent();
    initAdminVideo();
    initLogin();
    initRegister();
    initCalculator();
});