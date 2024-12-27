@extends('layouts.app')

@section('content')

    <style>
        .cart-total th, .cart-total td{
            color:green;
            font-weight: bold;
            font-size: 21px !important;
        }
    </style>

    <main class="pt-90">
        <div class="mb-4 pb-4"></div>
        <section class="shop-checkout container">
            <h2 class="page-title">Expédition et Paiement</h2>
            <div class="checkout-steps">
                <a href="{{route('cart.index')}}" class="checkout-steps__item active">
                    <span class="checkout-steps__item-number">01</span>
                    <span class="checkout-steps__item-title">
                <span>Panier</span>
                <em>Gérez votre liste d'articles</em>
            </span>
                </a>
                <a href="{{route('cart.checkout')}}" class="checkout-steps__item active">
                    <span class="checkout-steps__item-number">02</span>
                    <span class="checkout-steps__item-title">
                <span>Expédition et Paiement</span>
                <em>Validez votre liste d'articles</em>
            </span>
                </a>
                <a href="#" class="checkout-steps__item">
                    <span class="checkout-steps__item-number">03</span>
                    <span class="checkout-steps__item-title">
                <span>Confirmation</span>
                <em>Confirmation de la commande</em>
            </span>
                </a>
            </div>
            <form name="checkout-form" action="{{route('cart.place.order')}}" method="POST">
                @csrf
                <div class="checkout-form">
                    <div class="billing-info__wrapper">
                        <div class="row">
                            <div class="col-6">
                                <h4>DÉTAILS DE LIVRAISON</h4>
                            </div>
                            <div class="col-6">
                                @if($address)
                                    <a href="#" class="btn btn-info btn-sm float-right">Changer d'adresse</a>
                                    <a href="#" class="btn btn-warning btn-sm float-right mr-3">Modifier l'adresse</a>
                                @endif
                            </div>
                        </div>
                        @if($address)
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="my-account__address-list">
                                        <div class="my-account__address-item">
                                            <div class="my-account__address-item__detail">
                                                <p>{{$address->name}}</p>
                                                <p>{{$address->phone}}</p>
                                                <p>{{$address->address}}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="row mt-5">
                                <div class="col-md-6">
                                    <div class="form-floating my-3">
                                        <input type="text" class="form-control" name="name" value="{{old('name')}}">
                                        <label for="name">Nom complet *</label>
                                        <span class="text-danger">@error('name') {{$message}} @enderror</span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-floating my-3">
                                        <input type="text" class="form-control" name="phone" value="{{old('phone')}}">
                                        <label for="phone">Numéro de téléphone *</label>
                                        <span class="text-danger">@error('phone') {{$message}} @enderror</span>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="form-floating my-3">
                                        <input type="text" class="form-control" name="address" value="{{old('address')}}">
                                        <label for="address">Adresse *</label>
                                        <span class="text-danger">@error('address') {{$message}} @enderror</span>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                    <div class="checkout__totals-wrapper">
                        <div class="sticky-content">
                            <div class="checkout__totals">
                                <h3>Votre Commande</h3>
                                <table class="checkout-cart-items">
                                    <thead>
                                    <tr>
                                        <th>PRODUIT</th>
                                        <th class="text-right">SOUS-TOTAL</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach (Cart::instance('cart')->content() as $item)
                                        <tr>
                                            <td>
                                                {{$item->name}} x {{$item->qty}}
                                            </td>
                                            <td class="text-right">
                                                {{$item->subtotal}} FCFA
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                                @if(Session::has('discounts'))
                                    <table class="checkout-totals">
                                        <tbody>
                                        <tr>
                                            <th>Sous-total</th>
                                            <td class="text-right">{{Cart::instance('cart')->subtotal()}} FCFA</td>
                                        </tr>
                                        <tr>
                                            <th>Réduction {{Session("coupon")["code"]}}</th>
                                            <td class="text-right">-{{Session("discounts")["discount"]}} FCFA</td>
                                        </tr>
                                        <tr>
                                            <th>Sous-total après réduction</th>
                                            <td class="text-right">{{Session("discounts")["subtotal"]}} FCFA</td>
                                        </tr>
                                        <tr>
                                            <th>LIVRAISON</th>
                                            <td class="text-right">Gratuite</td>
                                        </tr>
                                        <tr>
                                            <th>TVA</th>
                                            <td class="text-right">{{Session("discounts")["tax"]}} FCFA</td>
                                        </tr>
                                        <tr class="cart-total">
                                            <th>Total</th>
                                            <td class="text-right">{{Session("discounts")["total"]}} FCFA</td>
                                        </tr>
                                        </tbody>
                                    </table>
                                @else
                                    <table class="checkout-totals">
                                        <tbody>
                                        <tr>
                                            <th>SOUS-TOTAL</th>
                                            <td class="text-right">{{Cart::instance('cart')->subtotal()}} FCFA</td>
                                        </tr>
                                        <tr>
                                            <th>LIVRAISON</th>
                                            <td class="text-right">Gratuite</td>
                                        </tr>
                                        <tr>
                                            <th>TVA</th>
                                            <td class="text-right">{{Cart::instance('cart')->tax()}} FCFA</td>
                                        </tr>
                                        <tr class="cart-total">
                                            <th>TOTAL</th>
                                            <td class="text-right">{{Cart::instance('cart')->total()}} FCFA</td>
                                        </tr>
                                        </tbody>
                                    </table>
                                @endif
                            </div>
                            <div class="checkout__payment-methods">
                                <div class="form-check">
                                    <input class="form-check-input form-check-input_fill" type="radio" name="mode" id="mode1" value="card">
                                    <label class="form-check-label" for="mode_1">
                                        Carte de crédit
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input form-check-input_fill" type="radio" name="mode" id="mode2" value="wave">
                                    <label class="form-check-label" for="mode_4">
                                        Wave
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input form-check-input_fill" type="radio" name="mode" id="mode3" value="om">
                                    <label class="form-check-label" for="mode_4">
                                        Orange Money
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input form-check-input_fill" type="radio" name="mode" id="mode4" value="cod" checked>
                                    <label class="form-check-label" for="mode_3">
                                        Paiement à la livraison
                                    </label>
                                </div>
                                <div class="policy-text">
                                    Vos données personnelles seront utilisées pour traiter votre commande.
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary">VALIDER LA COMMANDE</button>
                        </div>
                    </div>
                </div>
            </form>
        </section>

    </main>

@endsection
