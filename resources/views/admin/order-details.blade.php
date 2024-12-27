@extends('layouts.admin')
@section('content')
    <style>
        .table-transaction>tbody>tr:nth-of-type(odd) {
            --bs-table-accent-bg: #fff !important;
        }
    </style>
    <div class="main-content-inner">
        <div class="main-content-wrap">
            <div class="flex items-center flex-wrap justify-between gap20 mb-27">
                <h3>Détails de la commande</h3>
                <ul class="breadcrumbs flex items-center flex-wrap justify-start gap10">
                    <li>
                        <a href="{{route('admin.index')}}">
                            <div class="text-tiny">Dashboard</div>
                        </a>
                    </li>
                    <li>
                        <i class="icon-chevron-right"></i>
                    </li>
                    <li>
                        <div class="text-tiny">Détails de la commande</div>
                    </li>
                </ul>
            </div>

            <div class="wg-box">
                <div class="flex items-center justify-between gap10 flex-wrap">
                    <div class="wg-filter flex-grow">
                        <h5>Détails de la commande</h5>
                    </div>
                    <a class="tf-button style-1 w208" href="{{route('admin.orders')}}">Retour</a>
                </div>
                <div class="table-responsive">
                    @if(Session::has('status'))
                        <p class="alert alert-success">{{Session::get('status')}}</p>
                    @endif
                        <table class="table table-striped table-bordered table-transaction">
                            <tr>
                                <th>N° de commande</th>
                                <td>{{$order->id}}</td>
                                <th>Mobile</th>
                                <td>{{$order->phone}}</td>
                                {{--<th>Code Postal</th>
                                <td>{{$order->zip}}</td>--}}
                            </tr>
                            <tr>
                                <th>Date de commande</th>
                                <td>{{$order->created_at}}</td>
                                <th>Date de livraison</th>
                                <td>{{$order->delivered_date}}</td>
                            </tr>
                            <tr>
                                <th>Date d'annulation</th>
                                <td>{{$order->canceled_date}}</td>
                                <th>Statut de la commande</th>
                                <td>
                                    @if($order->status=='livrer')
                                        <span class="badge bg-success">Livré</span>
                                    @elseif($order->status=='annuler')
                                        <span class="badge bg-danger">Annulé</span>
                                    @else
                                        <span class="badge bg-warning">Commandé</span>
                                    @endif
                                </td>
                            </tr>
                        </table>

                </div>

                <div class="wg-box mt-5">
                    <div class="flex items-center justify-between gap10 flex-wrap">
                        <div class="wg-filter flex-grow">
                            <h5>Articles Commandés</h5>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered">
                            <thead>
                            <tr>
                                <th>Nom</th>
                                <th class="text-center">Prix</th>
                                <th class="text-center">Quantité</th>
                                {{--<th class="text-center">SKU</th>--}}
                                <th class="text-center">Catégorie</th>
                                <th class="text-center">Marque</th>
                                {{--<th class="text-center">Options</th>
                                <th class="text-center">Statut de Retour</th>--}}
                                <th class="text-center">Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($orderitems as $item)
                                <tr>
                                    <td class="pname">
                                        <div class="image">
                                            <img src="{{asset('uploads/products/thumbnails')}}/{{$item->product->image}}" alt="" class="image">
                                        </div>
                                        <div class="name">
                                            <a href="{{route('shop.product.details',["product_slug"=>$item->product->slug])}}" target="_blank" class="body-title-2">{{$item->product->name}}</a>
                                        </div>
                                    </td>
                                    <td class="text-center">{{$item->price}} FCFA</td>
                                    <td class="text-center">{{$item->quantity}}</td>
                                    {{--<td class="text-center">{{$item->product->SKU}}</td>--}}
                                    <td class="text-center">{{$item->product->category->name}}</td>
                                    <td class="text-center">{{$item->product->brand->name}}</td>
                                    {{--<td class="text-center">{{$item->options}}</td>
                                    <td class="text-center">{{$item->rstatus == 0 ? "Non":"Oui"}}</td>--}}
                                    <td class="text-center">
                                        <a href="{{route('shop.product.details',["product_slug"=>$item->product->slug])}}" target="_blank">
                                            <div class="list-icon-function view-icon">
                                                <div class="item eye">
                                                    <i class="icon-eye"></i>
                                                </div>
                                            </div>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="divider"></div>
                    <div class="flex items-center justify-between flex-wrap gap10 wgp-pagination">
                        {{$orderitems->links('pagination::bootstrap-5')}}
                    </div>
                </div>

                <div class="wg-box mt-5">
                    <h5>Adresse de Livraison</h5>
                    <div class="my-account__address-item col-md-6">
                        <div class="my-account__address-item__detail">
                            <p>{{$order->name}}</p>
                            <p>{{$order->address}}</p>
                            {{--<p>{{$order->locality}}</p>--}} <!-- Localité (commenté) -->
                            {{--<p>{{$order->city}}, {{$order->country}}</p>--}} <!-- Ville, Pays (commenté) -->
                            {{--<p>{{$order->landmark}}</p>--}} <!-- Point de Repère (commenté) -->
                            {{--<p>{{$order->zip}}</p>--}} <!-- Code Postal (commenté) -->
                            <br />
                            <p>Téléphone : {{$order->phone}}</p>
                        </div>
                    </div>
                </div>

                <div class="table-responsive">
                    <h5>Transactions</h5>
                    <table class="table table-striped table-bordered table-transaction">
                        <tr>
                            <th>Sous-total</th>
                            <td>{{$order->subtotal}} FCFA</td>
                            <th>Taxes</th>
                            <td>{{$transaction->order->tax}} FCFA</td>
                            {{--<th>Réduction</th>
                            <td>{{$transaction->order->discount}} FCFA</td>--}} <!-- Ligne commentée -->
                        </tr>
                        <tr>
                            <th>Total</th>
                            <td>{{$transaction->order->total}} FCFA</td>
                            <th>Mode de Paiement</th>
                            <td>{{$transaction->mode}}</td>
                        </tr>
                        <tr>
                            <th>Statut</th>
                            <td>
                                @if($transaction->status=='approuver')
                                    <span class="badge bg-success">Approuvé</span>
                                @elseif($transaction->status=='refuser')
                                    <span class="badge bg-danger">Refusé</span>
                                @elseif($transaction->status=='rembourser')
                                    <span class="badge bg-secondary">Remboursé</span>
                                @else
                                    <span class="badge bg-warning">En attente</span>
                                @endif
                            </td>
                        </tr>
                    </table>
                </div>

                <div class="table-responsive">
                    <div class="wg-box mt-5">
                        <h5>Mis à jour Statut Commande</h5>
                        <form action="{{route('admin.order.status.update')}}" method="POST">
                            @csrf
                            @method("PUT")
                            <input type="hidden" name="order_id" value="{{$order->id }}"  />
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="select">
                                        <select id="order_status" name="order_status">
                                            <option value="commander" {{ $order->status == "commander" ? "selected" : "" }}>Commander</option>
                                            <option value="livrer" {{ $order->status == "livrer" ? "selected" : "" }}>Livrer</option>
                                            <option value="annuler" {{ $order->status == "annuler" ? "selected" : "" }}>Annuler</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <button type="submit" class="btn btn-primary tf-button w208">Modifier Statut</button>
                                </div>
                            </div>
                        </form>
                    </div>

                </div>

        </div>
    </div>
@endsection
