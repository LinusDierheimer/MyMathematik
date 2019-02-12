$(document).resize(function(){
    console.log(2);
});

window.onscroll = function() {
    const header = document.getElementById("header");
    const sticky = header.offsetTop;
    if (window.pageYOffset > sticky) {
        header.classList.add("headersticky");
    } else {
        header.classList.remove("headersticky");
    }
};

function updatePanelWidths(){
    const chapterbuttons = document.getElementsByClassName("chaptertitle");
    for(var i = 0; i < chapterbuttons.length; i++){
        const panel = chapterbuttons[i].nextElementSibling;
        if(chapterbuttons[i].classList.contains("active"))
            panel.style.maxHeight = panel.scrollHeight + "px";
    }
}

window.onload = function(){

    const chapterbuttons = document.getElementsByClassName("chaptertitle");
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

            updatePanelWidths();

        });
    }

}