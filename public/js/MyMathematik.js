
/***************/
/*Sticky header*/
/**++++++++++++*/

window.onscroll = function() {
    const header = document.getElementById("header");
    const sticky = header.offsetTop;
    if (window.pageYOffset > sticky) {
        header.classList.add("headersticky");
    } else {
        header.classList.remove("headersticky");
    }
};