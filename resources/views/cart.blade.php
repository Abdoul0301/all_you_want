@extends("layouts.app")
@section("content")
    <style>
        .cart-totals td {
            text-align: right;
        }
        .cart-total th, .cart-total td {
            color: green;
            font-weight: bold;
            font-size: 21px !important;
        }
    </style>
    <main class="pt-90">
        <div class="mb-4 pb-4"></div>
        <section class="shop-checkout container">
            <h2 class="page-title">Panier</h2>
            <div class="checkout-steps">
                <a href="javascript:void(0)" class="checkout-steps__item active">
                    <span class="checkout-steps__item-number">01</span>
                    <span class="checkout-steps__item-title">
                        <span>Sac d'achat</span>
                        <em>Gérez votre liste d'articles</em>
                    </span>
                </a>
                <a href="javascript:void(0)" class="checkout-steps__item">
                    <span class="checkout-steps__item-number">02</span>
                    <span class="checkout-steps__item-title">
                        <span>Livraison et Paiement</span>
                        <em>Validez votre liste d'articles</em>
                    </span>
                </a>
                <a href="javascript:void(0)" class="checkout-steps__item">
                    <span class="checkout-steps__item-number">03</span>
                    <span class="checkout-steps__item-title">
                        <span>Confirmation</span>
                        <em>Vérifiez et soumettez votre commande</em>
                    </span>
                </a>
            </div>
            <div class="shopping-cart">
                @if($items->count() > 0)
                    <div class="cart-table__wrapper">
                        <table class="cart-table">
                            <thead>
                            <tr>
                                <th>Produit</th>
                                <th></th>
                                <th>Prix</th>
                                <th>Quantité</th>
                                <th>Sous-total</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($items as $item)
                                <tr>
                                    <td>
                                        <div class="shopping-cart__product-item">
                                            <img loading="lazy" src="{{asset('uploads/products/thumbnails')}}/{{$item->model->image}}" width="120" height="120" alt="" />
                                        </div>
                                    </td>
                                    <td>
                                        <div class="shopping-cart__product-item__detail">
                                            <h4>{{$item->name}}</h4>
                                            <ul class="shopping-cart__product-item__options">
                                                <li>Couleur : Jaune</li>
                                                <li>Taille : L</li>
                                            </ul>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="shopping-cart__product-price">{{$item->price}} FCFA</span>
                                    </td>
                                    <td>
                                        <div class="qty-control position-relative">
                                            <input type="number" name="quantity" value="{{$item->qty}}" min="1" class="qty-control__number text-center">
                                            <form method="POST" action="{{route('cart.qty.decrease',['rowId'=>$item->rowId])}}">
                                                @csrf
                                                @method('PUT')
                                                <div class="qty-control__reduce">-</div>
                                            </form>
                                            <form method="POST" action="{{route('cart.qty.increase',['rowId'=>$item->rowId])}}">
                                                @csrf
                                                @method('PUT')
                                                <div class="qty-control__increase">+</div>
                                            </form>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="shopping-cart__subtotal">{{$item->subTotal()}} FCFA</span>
                                    </td>
                                    <td>
                                        <form method="POST" action="{{route('cart.remove',['rowId'=>$item->rowId])}}">
                                            @csrf
                                            @method("DELETE")
                                            <a href="javascript:void(0)" class="remove-cart">
                                                <svg width="10" height="10" viewBox="0 0 10 10" fill="#767676" xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M0.259435 8.85506L9.11449 0L10 0.885506L1.14494 9.74056L0.259435 8.85506Z" />
                                                    <path d="M0.885506 0.0889838L9.74057 8.94404L8.85506 9.82955L0 0.97449L0.885506 0.0889838Z" />
                                                </svg>
                                            </a>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        <div class="cart-table-footer">
                            <form class="position-relative bg-body" method="POST" action="{{route('cart.empty')}}">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-light" type="submit">VIDEZ LE PANIER</button>
                            </form>
                        </div>
                    </div>

                    <div class="shopping-cart__totals-wrapper">
                        <div class="sticky-content">
                            <div class="shopping-cart__totals">
                                <h3>Total du panier</h3>
                                <table class="cart-totals">
                                    <tbody>
                                    <tr>
                                        <th>Sous-total</th>
                                        <td>{{Cart::instance('cart')->subtotal()}} FCFA</td>
                                    </tr>
                                    <tr>
                                        <th>Livraison</th>
                                        <td>Gratuit</td>
                                    </tr>
                                    <tr>
                                        <th>TVA</th>
                                        <td>{{Cart::instance('cart')->tax()}} FCFA</td>
                                    </tr>
                                    <tr>
                                        <th>Total</th>
                                        <td>{{Cart::instance('cart')->total()}} FCFA</td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="mobile_fixed-btn_wrapper">
                                <div class="button-wrapper container">
                                    <a href="{{route('cart.checkout')}}" class="btn btn-primary btn-checkout">PASSER À LA CAISSE</a>
                                </div>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="row">
                        <div class="col-md-12 text-center pt-5 pb-5">
                            <p>Aucun article trouvé dans votre panier</p>

                            <a href="{{route('shop.index')}}" class="btn btn-info">Achetez maintenant</a>
                        </div>
                    </div>
                @endif
            </div>
        </section>
    </main>
@endsection

@push("scripts")
    <script>
        $(function(){
            $(".qty-control__increase").on("click", function () {
                $(this).closest('form').submit();
            });

            $(".qty-control__reduce").on("click", function () {
                $(this).closest('form').submit();
            });

            $('.remove-cart').on("click", function () {
                $(this).closest('form').submit();
            });
        });
    </script>
@endpush
