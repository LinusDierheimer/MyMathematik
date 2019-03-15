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
                panel.style.maxHeight = null;
                panel.style.paddingTop = "0";
                image.className = "fas fa-sort-down";
            }else{
                panel.style.maxHeight = panel.scrollHeight + "px";
                panel.style.paddingTop = "10px";
                image.className = "fas fa-sort-up";
            }

            this.classList.toggle("active");

            updateAccordion();

        });
    }
}

function initCockieInfo(cookieinfo){
    if(!document.cookie.includes("cookies=true"))
        cookieinfo.classList.add("cookiesvisible");
}

function initCookieButton(cookieinfo){
    $("#cookiesAccepted").click(function(){
        document.cookie = "cookies=true; path=/";
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

function initLanguage(){
    if(!document.cookie.includes("language"))
        document.cookie = "language=de; path=/";
}

window.onload = function(){
    initAccordion();
    initCookies();
    initLanguage();
    initScrollUp();
}