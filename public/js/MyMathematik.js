
/***************/
/*Sticky header*/
/**++++++++++++*/

window.onscroll = function() {
    const header = document.getElementById("header");
    const sticky = header.offsetTop;
    if (window.pageYOffset > sticky) {
        header.classList.add("sticky");
    } else {
        header.classList.remove("sticky");
    }
};