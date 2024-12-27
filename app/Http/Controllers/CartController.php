<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use MongoDB\Driver\Session;
use Surfsidemedia\Shoppingcart\Facades\Cart;
class CartController extends Controller
{
    public function index()
    {
        $items = Cart::instance('cart')->content();
        return view('cart',compact('items'));
    }

    public function add_To_Cart(Request $request)
    {
        Cart::instance('cart')->add($request->id,$request->name,$request->quantity,$request->price)->associate('App\Models\Product');
        return redirect()->back();
    }

    public function increase_cart_quantity($rowId)
    {
        $product = Cart::instance('cart')->get($rowId);
        $qty = $product->qty + 1;
        Cart::instance('cart')->update($rowId,$qty);
        return redirect()->back();
    }

    public function decrease_cart_quantity($rowId)
    {
        $product = Cart::instance('cart')->get($rowId);
        $qty = $product->qty - 1;
        Cart::instance('cart')->update($rowId,$qty);
        return redirect()->back();
    }

    public function remove_item($rowId)
    {
        Cart::instance('cart')->remove($rowId);
        return redirect()->back();
    }

    public function empty_cart()
    {
        Cart::instance('cart')->destroy();
        return redirect()->back();
    }


    public function checkout()
    {
        if(!Auth::check())
        {
            return redirect()->route("login");
        }
        $address = Address::where('user_id',Auth::user()->id)->where('isdefault',1)->first();
        return view('checkout',compact("address"));
    }

    public function place_order(Request $request)
    {
        $user_id = Auth::user()->id;

        // Vérifier si une adresse par défaut existe
        $address = Address::where('user_id', $user_id)->where('isdefault', true)->first();
        if (!$address) {
            // Validation limitée aux champs requis
            $request->validate([
                'name' => 'required|max:100',
                'phone' => 'required|numeric|digits:9',
                'address' => 'required|max:255',
            ]);

            // Création d'une nouvelle adresse avec uniquement les champs nécessaires
            $address = new Address();
            $address->user_id = $user_id;
            $address->name = $request->name;
            $address->phone = $request->phone;
            $address->address = $request->address;
            $address->isdefault = true; // Définit l'adresse comme par défaut
            $address->save();
        }

        // Préparer les informations pour la commande
        $this->setAmountForCheckout();

        // Récupération sécurisée des données de session
        $checkout = session('checkout', [
            'subtotal' => 0,
            'discount' => 0,
            'tax' => 0,
            'total' => 0
        ]);

        // Création d'une commande
        $order = new Order();
        $order->user_id = $user_id;
        $order->subtotal = $this->formatDecimal($checkout['subtotal']);
        $order->discount = $this->formatDecimal($checkout['discount']);
        $order->tax = $this->formatDecimal($checkout['tax']);
        $order->total = $this->formatDecimal($checkout['total']);
        $order->name = $address->name;
        $order->phone = $address->phone;
        $order->address = $address->address;

        $order->save();



        // Ajouter les produits au panier dans la commande
        foreach (Cart::instance('cart')->content() as $item) {
            $orderitem = new OrderItem();
            $orderitem->product_id = $item->id;
            $orderitem->order_id = $order->id;
            $orderitem->price = $item->price;
            $orderitem->quantity = $item->qty;
            $orderitem->save();
        }

        // Gestion des modes de paiement
        if ($request->mode == "cod") {
            $transaction = new Transaction();
            $transaction->user_id = $user_id;
            $transaction->order_id = $order->id;
            $transaction->mode = $request->mode;
            $transaction->status = "attente";
            $transaction->save();
        }

        // Nettoyage du panier et des sessions
        Cart::instance('cart')->destroy();
        session()->forget('checkout');
        session()->forget('coupon');
        session()->forget('discounts');
        session()->put('order_id', $order->id);

        // Redirection vers la confirmation de commande
        return redirect()->route('cart.order.confirmation');
    }

    // Méthode utilitaire pour formater les décimaux
    private function formatDecimal($value)
    {
        // Convertit la valeur en nombre à virgule flottante
        $cleanValue = str_replace(',', '.', $value);
        return round((float)$cleanValue, 2);
    }


    public function setAmountForCheckout()
    {
        if(!Cart::instance('cart')->content()->count())
        {
            session()->forget('checkout');
            return;
        }

        // Utilisation de méthodes de formatage sécurisées
        $subtotal = Cart::instance('cart')->subtotal(2, '.', '');
        $tax = Cart::instance('cart')->tax(2, '.', '');
        $total = Cart::instance('cart')->total(2, '.', '');

        if (session()->has('coupon')) {
            $discounts = session('discounts', [
                'discount' => 0,
                'subtotal' => $subtotal,
                'tax' => $tax,
                'total' => $total
            ]);

            session()->put('checkout', [
                'discount' => $this->formatDecimal($discounts['discount']),
                'subtotal' => $this->formatDecimal($discounts['subtotal']),
                'tax' => $this->formatDecimal($discounts['tax']),
                'total' => $this->formatDecimal($discounts['total'])
            ]);
        } else {
            session()->put('checkout', [
                'discount' => 0,
                'subtotal' => $this->formatDecimal($subtotal),
                'tax' => $this->formatDecimal($tax),
                'total' => $this->formatDecimal($total)
            ]);
        }
    }

    public function order_confirmation()
    {
        if (session()->has('order_id'))
        {
            $order = Order::find(session()->get('order_id'));
            return view('order-confirmation',compact('order'));
        }
        return redirect()->route('cart.index');
    }

}
