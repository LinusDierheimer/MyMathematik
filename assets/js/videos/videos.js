import "../app.js";
import "../../scss/videos/videos.scss";

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

$(document).ready(function(){
    initAccordion();
});