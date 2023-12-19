@extends('client.layout.master')

@section('content')
<section class="shoping-cart spad">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="shoping__cart__table">
                    <table>
                        <thead>
                            <tr>
                                <th class="shoping__product">Products</th>
                                <th>Price</th>
                                <th>Quantity</th>
                                <th>Total</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody id="tbody-cart">
                            @php $total = 0 @endphp
                            @foreach ($cart as $productId => $item)
                                @php
                                     $total += $item['price'] * $item['qty'];
                                @endphp
                                <tr id="item-{{ $productId }}">
                                    <td class="shoping__cart__item">
                                        <img width="100" src="{{ $item['image'] }}" alt="">
                                        <h5>{{ $item['name'] }}</h5>
                                    </td>
                                    <td class="shoping__cart__price">
                                        ${{ $item['price'] }}
                                    </td>
                                    <td class="shoping__cart__quantity">
                                        <div class="quantity">
                                            <div class="pro-qty" data-url="{{ route('cart.update.item', ['id' => $productId]) }}">
                                                <input type="text" value="{{ $item['qty'] }}">
                                            </div>
                                        </div>
                                    </td>
                                    <td class="shoping__cart__total">
                                        $ {{ number_format($item['qty'] * $item['price'], 2) }}
                                    </td>
                                    <td class="shoping__cart__item__close">
                                        <span data-url="{{ route('cart.delete.item', ['id' => $productId]) }}" data-id={{ $productId }} class="icon_close"></span>
                                    </td>
                                </tr>    
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="shoping__cart__btns">
                    <a href="#" class="primary-btn cart-btn">CONTINUE SHOPPING</a>
                    <a href="#" class="remove-cart primary-btn cart-btn cart-btn-right"><span class="icon_close"></span>
                        Remove Cart</a>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="shoping__continue">
                    <div class="shoping__discount">
                        <h5>Discount Codes</h5>
                        <form action="#">
                            <input type="text" placeholder="Enter your coupon code">
                            <button type="submit" class="site-btn">APPLY COUPON</button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                    <div class="shoping__checkout">
                    <h5>Cart Total</h5>
                    <ul>
                        <li>Subtotal <span class="cart-subtotal">${{ number_format($total, 2) }}</span></li>
                        <li>Total <span class="cart-total">${{ number_format($total, 2  ) }}</span></li>
                    </ul>
                    <a href="#" class="primary-btn">PROCEED TO CHECKOUT</a>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@section('js-custom')
    <script type="text/javascript">
        $(document).ready(function(){
           $('.icon_close').on('click', function(){
                var id = $(this).data('id');
                var url = $(this).data('url');
                
                $.ajax({ // form
                    url: url, //action,
                    method: 'GET', //method,
                    success: function(response){
                        Swal.fire({
                            text: response.message,
                            icon: "success"
                        });
                        $('.number-item-in-cart').html(response.numberItem);
                        $('#item-'+ id).empty();
                        $('.cart-subtotal').html('$' + response.totalPrice);
                        $('.cart-total').html('$' + response.totalPrice);
                    },
                    statusCode: {
                        401: function() {
                            window.location.href = "{{ route('login') }}";
                        }
                    }
                });
           });

           $('.remove-cart').on('click', function(event){
                event.preventDefault();
                var url = "{{ route('cart.remove.cart') }}";
                
                $.ajax({ // form
                    url: url, //action,
                    method: 'GET', //method,
                    success: function(response){
                        Swal.fire({
                            text: response.message,
                            icon: "success"
                        });
                        $('.number-item-in-cart').html(response.numberItem);
                        $('#tbody-cart').empty();
                        $('.cart-subtotal').html('$' + response.totalPrice);
                        $('.cart-total').html('$' + response.totalPrice);
                    },
                    statusCode: {
                        401: function() {
                            window.location.href = "{{ route('login') }}";
                        }
                    }
                });
           });

           $('.pro-qty .qtybtn').on('click', function(){

                var element = $(this);
                var qty = parseInt($(this).siblings('input').val());

                if($(this).hasClass('dec')){
                    qty -= 1;
                }else{
                    qty += 1;
                }

                var url = $(this).parent().data('url') + '/' + qty;

                $.ajax({ // form
                    url: url, //action,
                    method: 'GET', //method,
                    success: function(response){
                        Swal.fire({
                            text: response.message,
                            icon: "success"
                        });
                        $('.number-item-in-cart').html(response.numberItem);
                        element.closest('tr').find('.shoping__cart__total').html("$ "+response.priceTotalItem);
                        $('.cart-subtotal').html('$' + response.totalPrice);
                        $('.cart-total').html('$' + response.totalPrice);
                    },
                    statusCode: {
                        401: function() {
                            window.location.href = "{{ route('login') }}";
                        }
                    }
                });
           })
        });
    </script>
@endsection
