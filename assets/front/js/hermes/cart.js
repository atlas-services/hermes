var $addProduct;
var $quantityProduct;
var $id;
var $quantity = 1;
var $url;
var $message= '';

jQuery(document).ready(function () {
    // add product to cart
    $addProduct = $('.post-product');
    $addProduct.on('click', function (e) {
        // active/desactive
        $id = $(this).attr('id');
        $quantity = $(this).data('quantity');
        $type = $(this).data('type');
        $url = "/panier";
        $message = '';
        if($('#alert').length == 0){
            $message = '<div id="alert" class="col-lg-6 mx-auto mt-3 alert alert-success ">\n' +
                '            <a href="#" class="hidden close" data-dismiss="alert" aria-label="close">&times;</a>\n' +
                '            Cet article a bien été <strong>ajouté à votre panier</strong>!.\n' +
                '        </div>';
        }

        handleProductCart($url, $id, $quantity, $type , $message);
    });

    // Cart quantity
    // update product quantity
    $quantityProduct = '.product-quantity';
    $(document).on('change', $quantityProduct, function(e) {
        if (e.handled !== true) {
            // change quantity
            $id = $(this).attr('id');
            $quantity = $(this).val();
            $type = $(this).data('type');
            $url = "/panier";
            handleProductCart($url, $id, $quantity, $type , '');
            e.handled = true;
            return;
        }
    });
});



function handleProductCart($url, $id, $quantity,$type, $message) {

    $.ajax({
        type: "POST",
        url: $url,
        data: {
            id: $id,
            quantity: $quantity,
            type: $type,
        },
        dataType: "json",
        success: function (response) {
            // update menu cart
            $('#menu-cart').replaceWith(response.data.navbar_cart_html);
            $('#cart-table').replaceWith(response.data.cart_html);

            // panier vide
            if(0 == response.data.total){
                window.location.replace("/");
            }
            // message produit ajouté au panier
            if('' != $message){
                // document.getElementById($id).insertAdjacentHTML('afterend',$message);
                document.getElementById('top').insertAdjacentHTML('afterend',$message);
            }
        }
    });

}