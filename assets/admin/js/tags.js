import $ from 'jquery';
const jQuery = $;

var $collectionHolder;

// setup an "add a tag" link
var $addTagButton = $('<button id="add-tag" type="button" class="btn btn-primary add_tag_link"><i class="fa fa-plus-circle"></i> tag</button>');
var $newLinkLi = $('<li class="list-unstyled btn btn-primary add-tag"></li>').append($addTagButton);

jQuery(document).ready(function() {
    // Get the ul that holds the collection of tags
    $collectionHolder = $('ul.tags');

    // add the "add a tag" anchor and li to the tags ul
    $collectionHolder.append($newLinkLi);

    // count the current form inputs we have (e.g. 2), use that as the new
    // index when inserting a new item (e.g. 2)
    $collectionHolder.data('index', $collectionHolder.find('input').length);

    $addTagButton.on('click', function(e) {
        // add a new tag form (see next code block)
        addTagForm($collectionHolder, $newLinkLi);
    });

    // add a delete link to all of the existing tag form li elements
    $collectionHolder.find('li').each(function() {
        addTagFormDeleteLink($(this));
    });
});

function addTagForm($collectionHolder, $newLinkLi) {
    // Get the data-prototype explained earlier
    var prototype = $collectionHolder.data('prototype');

    // get the new index
    var index = $collectionHolder.data('index');

    var newForm = prototype;
    // You need this only if you didn't set 'label' => false in your tags field in TaskType
    // Replace '__name__label__' in the prototype's HTML to
    // instead be a number based on how many items we have
    // newForm = newForm.replace(/__name__label__/g, index);

    // Replace '__name__' in the prototype's HTML to
    // instead be a number based on how many items we have
    newForm = newForm.replace(/__name__/g, index);

    // increase the index with one for the next item
    $collectionHolder.data('index', index + 1);

    // Display the form in the page in an li, before the "Add a tag" link li
    var $newFormLi = $('<li class="list-unstyled btn btn-primary new-tag"></li>').append(newForm);
    $newLinkLi.before($newFormLi);
    // add a delete link to the new form
    addTagFormDeleteLink($newFormLi);
}

function addTagFormDeleteLink($tagFormLi) {
    var $removeFormButton = $('<button class="btn btn-danger " type="button"><i class="fa fa-trash"></i></button>');
    if($tagFormLi.attr('class').includes("add-tag")){
      }else{
         if($tagFormLi.attr('class').includes("new-tag")){
            $tagFormLi.children().children().append($removeFormButton);
        }else{
             $tagFormLi.children().append($removeFormButton);
         }
    }

    $removeFormButton.on('click', function(e) {
        // remove the li for the tag form
        $tagFormLi.remove();
    });
}