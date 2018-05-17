/* NAV BAR JEDI*/
$("#burger").click(function() {
    $(this).toggleClass("active");
    $("nav").toggleClass("show");
});

/* Background */
$(document).ready(function(){
    $('#intro').parallax("50%", 0.1);
    $('#second').parallax("50%", 0.1);
    $('.bg').parallax("0%", 0.3);
    $('#third').parallax("50%", 0.3);
})