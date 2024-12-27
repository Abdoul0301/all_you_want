@extends('layouts.app')
@section('content')
    <main class="pt-90">
        <div class="mb-4 pb-4"></div>
        <section class="my-account container">
            <h2 class="page-title">Mon compte</h2>
            <div class="row">
                <div class="col-lg-3">
                    @include('user.account-nav')
                </div>
                <div class="col-lg-9">
                    <div class="page-content my-account__dashboard">
                        <h3><strong>Bienvenue {{Auth::user()->name}} chez All You Want</strong></h3>
                         </div>
                </div>
            </div>
        </section>
    </main>
@endsection
