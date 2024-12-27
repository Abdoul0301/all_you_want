@extends('layouts.app')
@section('content')
    <style>
        .table > :not(caption) > tr > th {
            padding: 0.625rem 1.5rem .625rem !important;
            background-color: #6a6e51 !important;
        }
        .table  > tr > td {
            padding: 0.625rem 1.5rem .625rem !important;
        }
        .table-bordered > :not(caption) > tr > th, .table-bordered > :not(caption) > tr > td {
            border-width: 1px 1px;
            border-color: #6a6e51;
        }
        .table > :not(caption) > tr > td {
            padding: .8rem 1rem !important;
        }
        /* Couleurs pour les badges des statuts */
        .badge-livrer {
            background-color: #28a745 !important; /* Vert plus vif */
            color: white !important;
        }
        .badge-annuler {
            background-color: #dc3545 !important; /* Rouge vif */
            color: white !important;
        }
        .badge-commander {
            background-color: #ffc107 !important; /* Jaune vif */
            color: black !important;
        }
        /* Ajustement général pour les badges */
        .badge {
            padding: 0.5rem 1rem;
            font-size: 0.9rem;
            border-radius: 0.5rem;
        }

    </style>

    <main class="pt-90" style="padding-top: 0px;">
        <div class="mb-4 pb-4"></div>
        <section class="my-account container">
            <h2 class="page-title">Commandes</h2>
            <div class="row">

                <div class="col-lg-2">
                    @include('user.account-nav')
                </div>

                <div class="col-lg-10">
                    <div class="table-responsive">
                    <table class="table table-striped table-bordered">
                        <thead>
                        <tr>
                            <th style="width: 80px">N° de commande</th>
                            <th>Nom</th>
                            <th class="text-center">Téléphone</th>
                            <th class="text-center">Sous-total</th>
                            <th class="text-center">Taxe</th>
                            <th class="text-center">Total</th>
                            <th class="text-center">Statut</th>
                            <th class="text-center">Date de commande</th>
                            <th class="text-center">Articles</th>
                            <th class="text-center">Livré le</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($orders as $order)
                            <tr>
                                <td class="text-center">{{$order->id}}</td>
                                <td class="text-center">{{$order->name}}</td>
                                <td class="text-center">{{$order->phone}}</td>
                                <td class="text-center">{{$order->subtotal}} FCFA</td>
                                <td class="text-center">{{$order->tax}} FCFA</td>
                                <td class="text-center">{{$order->total}} FCFA</td>
                                <td class="text-center">
                                    @if($order->status == 'livrer')
                                        <span class="badge badge-livrer">Livré</span>
                                    @elseif($order->status == 'annuler')
                                        <span class="badge badge-annuler">Annulé</span>
                                    @else
                                        <span class="badge badge-commander">Commandé</span>
                                    @endif
                                </td>
                                <td class="text-center">{{$order->created_at}}</td>
                                <td class="text-center">{{$order->orderItems->count()}}</td>
                                <td class="text-center">{{$order->delivered_date}}</td>
                                <td class="text-center">
                                    <a href="{{route('user.order.details',["order_id"=>$order->id])}}" target="_blank">
                                        <div class="list-icon-function view-icon">
                                            <div class="item eye">
                                                <i class="fa fa-eye"></i>
                                            </div>
                                        </div>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                </div>


            </div>
        </section>
    </main>
@endsection
