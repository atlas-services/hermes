/*
search
 */
var duration = 400;
var easing = "swing";
$('#search_icon').on("mouseover", e => {
    $('#search_bar').css('visibility', 'visible');
    $('#search_bar').show(duration, easing);
});

$('#search_icon').on("click", e => {
    $('#search_bar').css('visibility', 'visible');
    $('#search_bar').show(duration, easing);
});

$('#search_bar').on("mouseout", e => {
    if ('' == $('#search_bar').val()){
        $('#search_bar').hide(duration, easing);
    }
});

$('.content').on("mouseover", e => {
    if ('' == $('#search_bar').val()){
        $('#search_bar').hide(duration, easing);
    }
});
