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

    const $header = $("#header");

    const $content = $("#menucontent");
    function setMaxHeight() {
        const maxHeight = window.innerHeight - $header.height() - 2; //we don't want to touch bottom
        $content.css("max-height", maxHeight);
    }
    window.addEventListener('resize', setMaxHeight);
    setMaxHeight();

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
    function setMinHeight() {
        const $fullContent = $("#fullcontent");
        const minHeight = window.innerHeight - $("#footer").height();
        $fullContent.css("min-height", minHeight);
    }
    window.addEventListener('resize', setMinHeight);
    setMinHeight();
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

function initLogin() {
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

function initRegister(){
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

function addMissingBrackets(raw){
    var numberOfStartingBrackets = raw.split("(").length - 1;
    var numberOfClosingBrackets = raw.split(")").length - 1;
    if(numberOfStartingBrackets > numberOfClosingBrackets)
        raw = raw + ")".repeat(numberOfStartingBrackets - numberOfClosingBrackets);
    else if(numberOfStartingBrackets < numberOfClosingBrackets)
        raw = "(".repeat(numberOfClosingBrackets - numberOfStartingBrackets) + raw;
    return raw;
}

function evalMathString(raw, lastResult = "0") {
    raw = raw
        .split("÷").join("/")
        .split("×").join("*")
        .split("∞").join("Infinity")
        .split("ANS").join(lastResult)
        .split("rand(").join("rand(")
        .split("RAND").join("rand()")
        .split("sqrt(").join("Math.sqrt(")
        .split("abs(").join("Math.abs(")
        .split("sin(").join("Math.sin(")
        .split("cos(").join("Math.cos(")
        .split("tan(").join("Math.tan(")
        .split("sinh(").join("Math.sinh(")
        .split("cosh(").join("Math.cosh(")
        .split("tanh(").join("Math.tanh(")
        .split("asin(").join("Math.asin(")
        .split("acos(").join("Math.acos(")
        .split("atan(").join("Math.atan(")
        .split("asinh(").join("Math.asinh(")
        .split("acosh(").join("Math.acosh(")
        .split("atanh(").join("Math.atanh(")
        .split("ln(").join("Math.log(")
        .split("lg(").join("Math.log10(")
        .split("log(").join("log(")
        .split("pow(").join("Math.pow(")
        .split("exp(").join("Math.exp(")
        .split("floor(").join("Math.floor(")
        .split("ceil(").join("Math.ceil(")
        .split("round(").join("Math.round(")
        .split("trunc(").join("Math.trunc(")
        .split("cbrt(").join("Math.cbrt(")
        .split("fac(").join("fac(")
        .split("π").join("Math.PI")
        .split("ℯ").join("Math.E")
        .split(";").join(",");

    console.info("Prepaired statement: " + raw);

    //Functions used by eval()

    function fac(x){
        return x <= 2 ? 2 : x * fac(x - 1);
    }

    function log(base, x){
        return Math.log(x) / Math.log(base);
    }

    function rand(min, max) {
        if(min != undefined && max != undefined)
            return Math.floor(Math.random() * (+max - +min)) + +min;
        else if(min != undefined)
            return Math.floor(Math.random() * min)
        else
            return Math.random();
    }

    var res = "";
    try {
        res = '' + eval(raw);
    } catch (error) {
        res = '<span class="error">' + error.message + '</span>';
    }

    res = res
        .split("Infinity").join("∞");

    return res;
}

function initCalculator() {
    const $output = $("#calculatorOutput");
    const $calculation = $(".calculation");
    const $buttons = $(".calculatorButton");
    const $equalButton = $(".equalButton");
    const $acButton = $(".acButton");
    const $cButton = $(".cButton");
    const $lastButton = $(".lastButton");
    const $invertButton = $(".invertButton");
    const $history = $(".historyentrys");

    var lastRaw = "";
    var lastResult = "";

    function isInverted() {
        return $invertButton.data("inverted") == "true";
    }

    $invertButton.click(function(){
        const useNormal = isInverted();
        $buttons.each(function(){
            const $e = $(this);
            if(useNormal){
                const data = $e.data("normal");
                if(data)
                    $e.html(data);
            }else {
                const data = $e.data("inverted");
                if(data)
                    $e.html(data);
            }
        });
        if(useNormal){
            $invertButton.data("inverted", "false");
            $invertButton.css("background", "lightcoral");
        }else {
            $invertButton.data("inverted", "true");
            $invertButton.css("background", "coral");
        }
    });

    $buttons.click(function(){
        const $this = $(this);
        var add = $this.data("normal-value");
        
        if(isInverted()){
            const invertedAdd = $this.data("inverted-value");
            if(invertedAdd)
                add = invertedAdd;
        }

        $output.html($output.html() + add);
    });

    $lastButton.click(function(){
        $output.html(lastRaw);
    });

    $acButton.click(function(){
        $output.html("");
    });

    $cButton.click(function(){
        const str = $output.html();
        $output.html(str.substring(0, str.length - 1));
    });

    function calculate(){
        lastRaw = addMissingBrackets($output.text());
        $output.text("...");

        lastResult = evalMathString(lastRaw, lastResult);

        $history.prepend(`
            <div class="entry">
                <span class="raw inputsetter">${lastRaw}</span>
                <span class="equal">=</span>
                <span class="result inputsetter">${lastResult}</span>
            </div>
        `).find(".inputsetter").unbind('click').click(function(){
            $output.append($(this).html());
        });

        $calculation.html(lastRaw + " = " + lastResult);
        $output.html(lastResult);
    }

    $(document).on('keypress',function(e) {
        if(e.which == 13){
            e.preventDefault();
            e.stopPropagation();
            calculate();
        }
    });

    $equalButton.click(calculate);
}

function initPost(){
    const $create = $("#createanswer");
    const $buttons = $(".createanswerbutton");
    const $cancle = $(".canclecreatepost");

    $buttons.click(function() {
        $buttons.css("display", "none");
        $create.css("display", "block");
    });

    $cancle.click(function() {
        $buttons.css("display", "inline-block");
        $create.css("display", "none");
    })
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
    initLogin();
    initRegister();
    initCalculator();
    initPost();
});