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
                image.src = "/assets/arrow_down.svg";
            }else{
                panel.style.maxHeight = panel.scrollHeight + "px";
                panel.style.paddingTop = "10px";
                image.src = "/assets/arrow_up.svg";
            }

            this.classList.toggle("active");

            updateAccordion();

        });
    }
}

function initCookies(){
    if(!document.cookie.includes("cookies=true"))
        document.getElementById("cookies").classList.add("cookiesvisible");
}

function initLanguage(){
    if(!document.cookie.includes("language"))
        document.cookie = "language=de; path=/";
}

function cookiesaccepted(){
    document.cookie = "cookies=true; path=/";
    document.getElementById("cookies").classList.remove("cookiesvisible");

}

function goup(){
    $('html, body').animate({scrollTop:0}, 'slow');
}

window.onload = function(){
    initAccordion();
    initCookies();
    initLanguage();
}