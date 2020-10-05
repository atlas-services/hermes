var $activePost;
var $id;
var $url;

// setup an "add a tag" link
var $addTagButton = $('<button type="button" class="btn btn-primary add_tag_link">Add a tag</button>');
var $newLinkLi = $('<li class="list-unstyled btn btn-primary"></li>').append($addTagButton);

jQuery(document).ready(function () {
    // Switch active post
    $activePost = $('.post-active');
    $activePost.on('click', function (e) {
        // active/desactive
        $id = $(this).attr('id');
        $url = "/fr/admin/ajax/switch/post";
        switchActive($url, $id);
    });
    // Switch active page (section)
    $activeSection = $('.section-active');
    $activeSection.on('click', function (e) {
        // active/desactive
        $id = $(this).attr('id');
        $url = "/fr/admin/ajax/switch/section";
        switchActive($url, $id);
    });
    // Switch active sous-menu (menu)
    $activeMenu = $('.menu-active');
    $activeMenu.on('click', function (e) {
        // active/desactive
        $id = $(this).attr('id');
        $url = "/fr/admin/ajax/switch/menu";
        switchActive($url, $id);
    });
    // Switch active menu (sheet)
    $activeSheet = $('.sheet-active');
    $activeSheet.on('click', function (e) {
        // active/desactive
        $id = $(this).attr('id');
        $url = "/fr/admin/ajax/switch/sheet";
        switchActive($url, $id);
    });
    // Switch active temoignage
    $activeMenu = $('.temoignage-active');
    $activeMenu.on('click', function (e) {
        // active/desactive
        $id = $(this).attr('id');
        $url = "/fr/admin/ajax/switch/temoignage";
        switchActive($url, $id);
    });
});

function switchActive($url, $id) {

    $.ajax({
        type: "POST",
        url: $url,
        data: {
            id: $id
        },
        dataType: "json",
        success: function (response) {
        }
    });

}