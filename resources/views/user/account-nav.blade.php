<ul class="account-nav">
    <li><a href="{{route('user.index')}}" class="menu-link menu-link_us-s">Dashboard</a></li>
    <li><a href="{{route('user.orders')}}" class="menu-link menu-link_us-s">Commandes</a></li>
    {{--<li><a href="account-address.html" class="menu-link menu-link_us-s">Adresses</a></li>--}}
    {{--<li><a href="account-details.html" class="menu-link menu-link_us-s">Détails du compte</a></li>--}}
    <li><a href="{{route('wishlist.index')}}" class="menu-link menu-link_us-s">favoris</a></li>
    <li>
        <form method="POST" action="{{route('logout')}}" id="logout-form">
            @csrf
            <a href="{{route('logout')}}" class="menu-link menu-link_us-s" onclick="event.preventDefault();document.getElementById('logout-form').submit();"> Logout</a>
        </form>
    </li>
</ul>
