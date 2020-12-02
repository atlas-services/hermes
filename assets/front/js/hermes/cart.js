var $addProduct;
var $id;
var $quantity = 1;
var $url;


jQuery(document).ready(function () {
    // add product to cart
    $addProduct = $('.post-product');
    $addProduct.on('click', function (e) {
        // active/desactive
        $id = $(this).attr('id');
        $quantity = $(this).attr('quantity');
        $url = "/cart";
        updateCart($url, $id);
    });
});

function updateCart($url, $id) {

    $.ajax({
        type: "POST",
        url: $url,
        data: {
            id: $id,
            quantity: $quantity,
        },
        dataType: "json",
        success: function (response) {
            var text_alert = '<div id="alert" class="col-lg-6 mx-auto mt-3 alert alert-success ">\n' +
                '            <a href="#" class="hidden close" data-dismiss="alert" aria-label="close">&times;</a>\n' +
                '            Le produit a bien été <strong>ajouté à votre panier</strong>!.\n' +
                '        </div>';
            document.getElementById($id).insertAdjacentHTML('afterend',text_alert);
        }
    });

}