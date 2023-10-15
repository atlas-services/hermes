import $ from 'jquery';
const jQuery = $;

var $activePost;
var $activeSection;
var $activeMenu;
var $activeSheet;
var $id;
var $url;

// setup an "add a tag" link
var $addTagButton = $('<button type="button" class="btn btn-primary add_tag_link">Add a tag</button>');
var $newLinkLi = $('<li class="list-unstyled btn btn-primary"></li>').append($addTagButton);

jQuery(document).ready(function () {
    // Switch active post
    var $activePost = $('.post-active');
    $activePost.on('click', function (e) {
        // active/desactive
        $id = $(this).attr('id');
        $url = "/fr/admin/ajax/switch/post";
        switchActive($url, $id);
    });
    // Switch active page (section)
    var $activeSection = $('.section-active');
    $activeSection.on('click', function (e) {
        // active/desactive
        $id = $(this).attr('id');
        $url = "/fr/admin/ajax/switch/section";
        switchActive($url, $id);
    });
    // Switch active sous-menu (menu)
    var $activeMenu = $('.menu-active');
    $activeMenu.on('click', function (e) {
        // active/desactive
        $id = $(this).attr('id');
        $url = "/fr/admin/ajax/switch/menu";
        switchActive($url, $id);
    });
    // Switch active menu (sheet)
    var $activeSheet = $('.sheet-active');
    $activeSheet.on('click', function (e) {
        // active/desactive
        $id = $(this).attr('id');
        $url = "/fr/admin/ajax/switch/sheet";
        switchActive($url, $id);
    });
    // Switch active temoignage
    var $activeMenu = $('.temoignage-active');
    $activeMenu.on('click', function (e) {
        // active/desactive
        $id = $(this).attr('id');
        $url = "/fr/admin/ajax/switch/temoignage";
        switchActive($url, $id);
    });
    // Switch active blockpost
    var $activeBlockPost = $('.blockpost-active');
    $activeBlockPost.on('click', function (e) {
        // active/desactive
        $id = $(this).attr('id');
        $url = "/fr/admin/ajax/switch/blockpost";
        switchActive($url, $id);
    });
    // Switch active block
    var $activeBlock = $('.block-active');
    $activeBlock.on('click', function (e) {
        // active/desactive
        $id = $(this).attr('id');
        $url = "/fr/admin/ajax/switch/block";
        switchActive($url, $id);
    });
    // Switch active_newsletter user (user)
    var $activeUser = $('.user-active');
    $activeUser.on('click', function (e) {
        // active/desactive
        $id = $(this).attr('id');
        $url = "/fr/admin/user/ajax/switch/user";
        switchActive($url, $id);
        //let switchvalue = document.getElementById($(this).attr('id')).getAttribute("checked");
        // if('' == switchvalue){
        //     document.getElementById('users-active').setAttribute("checked", ''); // 'checked="0'"
        // }
        
    });
    // Switch active_newsletter users (user)
    var $activeUsers = $('.users-active');
    $activeUsers.on('click', function (e) {
        // active/desactive
        $url = "/fr/admin/user/ajax/switch/users";
        switchActiveAll($url);
        let actives = document.getElementsByClassName('user-active');
        Array.from(actives).forEach( (el) => {
            el.setAttribute("checked", true);
          });
       
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

function switchActiveAll($url) {

    $.ajax({
        type: "POST",
        url: $url,
        dataType: "json",
        success: function (response) {
        }
    });

}