$(document).ready(function () {
    $('.add-to-cart-form').on('submit', function (e) {
        e.preventDefault();

        let form = $(this);
        let productId = form.find('input[name="product_id"]').val();
        let quantity = $('#quantity-' + productId).val() || 1;

        form.find('#input-quantity-' + productId).val(quantity);

        $.ajax({
            url: form.attr('action'),
            method: 'POST',
            data: form.serialize(),
            success: function (response) {
                alert(response.message);
                updateCartCount(response.newCartCount, response.newCartTotalPrice);
            },
            error: function (xhr) {
                let errorMessage = xhr.responseJSON && xhr.responseJSON.error ? xhr.responseJSON.error : 'An error occurred. Please try again.';
                alert('Error: ' + errorMessage);
            }
        });
    });

    $('.update-btn').on('click', function () {
        let cartId = $(this).data('cart-id');
        let quantity = $('input[data-cart-id="' + cartId + '"]').val();

        $.ajax({
            url: '/cart/update/' + cartId,
            method: 'POST',
            data: {
                quantity: quantity,
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            success: function (response) {
                alert(response.message);
                $('#cart-item-' + cartId + ' .total-price').text(response.newPrice + ' $');
                $('#cart-item-' + cartId + ' .item-quantity').text(response.newQuantity);// Update the quantity of the product
                $('#item-price-' + cartId).text(response.newPrice);
                // Update the number of items in the cart in the navigation
                updateCartCount(response.newCartCount, response.newCartTotalPrice);
            },
            error: function (xhr) {
                let errorMessage = 'Error updating quantity';
                if (xhr.responseJSON && xhr.responseJSON.error) {
                    errorMessage = xhr.responseJSON.error;
                }
                alert(errorMessage);
            }
        });
    });

    $('.remove-btn').on('click', function () {
        let cartId = $(this).data('cart-id');
        let quantityToRemove = $('input[data-cart-id="' + cartId + '"]').val();

        if (quantityToRemove <= 0 || isNaN(quantityToRemove)) {
            alert('Invalid quantity');
            return;
        }

        $.ajax({
            url: '/cart/remove/' + cartId,
            method: 'DELETE',
            data: {
                _token: $('meta[name="csrf-token"]').attr('content'),
                quantity: quantityToRemove
            },
            success: function (response) {
                alert(response.message);

                if (response.newItemQuantity > 0) {

                    $('input[data-cart-id="' + cartId + '"]').val(response.newItemQuantity);
                    $('#item-price-' + cartId).text(response.newItemPrice);
                } else {
                    $('#cart-item-' + cartId).remove();
                }

                updateCartCount(response.newCartCount, response.newCartTotalPrice);
            },
            error: function (xhr) {
                let errorMessage = 'Error while deleting item';
                if (xhr.responseJSON && xhr.responseJSON.error) {
                    errorMessage = xhr.responseJSON.error;
                }
                alert(errorMessage);
            }
        });
    });

    // Updates counter in navigation
    function updateCartCount(newCartCount, newCartTotalPrice) {
        $('#cartCount').text(newCartCount);
        $('#total-items').text(newCartCount);
        $('#total-price').text(newCartTotalPrice);
    }
});
