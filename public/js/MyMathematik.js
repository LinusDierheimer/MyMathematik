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

            for(var i = 0; i < chapterbuttons.length; i++){
                const panel = chapterbuttons[i].nextElementSibling;
                if(chapterbuttons[i].classList.contains("active"))
                    panel.style.maxHeight = panel.scrollHeight + "px";
            }

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

window.onload = function(){
    initAccordion();
    initCookies();
    initLanguage();
}