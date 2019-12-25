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
        $cookieinfo.animate({ height: $cookieinfo[0].scrollHeight }, 10);

    $("#cookiesAccepted").click(function(){
        setCookie("cookies", "true");
        $cookieinfo.animate({ height:0 },200);
    });
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
                design = "dark";
            else
                design = "white";
        }

        removeAllClassesStartingWith("body", "design-");
        $("body").addClass("design-" + design);
    }

    setDesign(prev_design); //inital design set

    const updateWhenSystemDesign = function(e) {
        if(prev_design == "system")
            setDesign("system");
    }

    window.matchMedia("(prefers-color-scheme: dark)").addListener(updateWhenSystemDesign);
    window.matchMedia("(prefers-color-scheme: light)").addListener(updateWhenSystemDesign);

    $(".designswitcher").click(function(){
        setDesign($(this).data("design-name"));
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

function initTextAreas()
{
    $(document)
        .one('focus.autoexpand', 'textarea.autoexpand', function(){
            var savedValue = this.value;
            this.value = '';
            this.baseScrollHeight = this.scrollHeight;
            this.value = savedValue;
        })
        .on('input.autoexpand', 'textarea.autoexpand', function(){
            var minRows = this.getAttribute('data-min-rows')|0, rows;
            this.rows = minRows;
            rows = Math.ceil((this.scrollHeight - this.baseScrollHeight) / 16);
            this.rows = minRows + rows;
        });
}

export function initCommon(){
    initMenuBar();
    initBody();
    initCookies();
    initDesign();
    initLanguage();
    initScrollUp();
    initTextAreas();
}