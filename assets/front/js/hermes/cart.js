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
        if($('#addArticleModale').length == 0){
            // $message = '<div id="alert" class="col-lg-6 mx-auto mt-3 alert alert-success ">\n' +
            //     '            <a href="#" class="hidden close" data-dismiss="alert" aria-label="close">&times;</a>\n' +
            //     '            Cet article a bien été <strong>ajouté à votre panier</strong>!.\n' +
            //     '        </div>';

            $message = '<div class="col-lg-12 mx-auto px-5 modal fade" id="addArticleModale" tabindex="-1" role="dialog" aria-labelledby="addArticleModaleLabel" aria-hidden="true">\n' +
                '    <div class="modal-dialog col-lg-8 mw-100 " role="document">\n' +
                '        <div class="modal-content col-lg-12">\n' +
                '             <div class="modal-header">\n' +
                '                <button type="button" class="close" data-dismiss="modal" aria-label="Close">\n' +
                '                    <span aria-hidden="true">&times;</span>\n' +
                '                </button>\n' +
                '            </div>\n' +
                '            <h5 class="modal-title text-center my-5 " id="addArticleModaleLabel">Cet article a été <strong>ajouté à votre panier</strong>!</h5>\n' +
                '        </div>\n' +
                '    </div>\n' +
                '</div>';

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
            // if('' != $message) {
                if($('#addArticleModale').length == 0){
                    document.getElementById('portfolio').insertAdjacentHTML('afterend', $message);
                }
                $('#addArticleModale').modal('show');
                setTimeout(function(){
                    $('#addArticleModale').modal('hide')
                }, 500);
            // }
        }
    });

}