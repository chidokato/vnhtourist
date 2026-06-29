<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;

class CartController extends BaseFrontendController
{
    public function index()
    {
        $cart = session()->get('cart', []);
        $total = array_sum(array_column($cart, 'total'));

        return view('frontend.cart.index', $this->sharedViewData(compact('cart', 'total')));
    }

    public function add(Request $request)
    {
        $request->validate([
            'post_id' => 'required|exists:posts,id',
            'adult_quantity' => 'required|integer|min:1',
            'child_quantity' => 'nullable|integer|min:0',
            'infant_quantity' => 'nullable|integer|min:0',
            'departure_date' => 'nullable|string',
        ]);

        $post = Post::findOrFail($request->post_id);
        $adultQty = (int) $request->input('adult_quantity', 1);
        $childQty = (int) $request->input('child_quantity', 0);
        $infantQty = (int) $request->input('infant_quantity', 0);
        
        $basePrice = $post->price;
        $childPrice = $post->child_price_percent ? ($basePrice * $post->child_price_percent / 100) : 0;
        $infantPrice = $post->infant_price_percent ? ($basePrice * $post->infant_price_percent / 100) : 0;

        $totalPrice = ($adultQty * $basePrice) + ($childQty * $childPrice) + ($infantQty * $infantPrice);

        $cart = session()->get('cart', []);

        $cartKey = $post->id . '_' . ($request->departure_date ?: 'none');

        if (isset($cart[$cartKey])) {
            $cart[$cartKey]['adult_quantity'] += $adultQty;
            $cart[$cartKey]['child_quantity'] += $childQty;
            $cart[$cartKey]['infant_quantity'] += $infantQty;
            $cart[$cartKey]['total'] += $totalPrice;
        } else {
            $cart[$cartKey] = [
                'post_id' => $post->id,
                'tour_name' => $post->title,
                'adult_quantity' => $adultQty,
                'child_quantity' => $childQty,
                'infant_quantity' => $infantQty,
                'price' => $basePrice,
                'total' => $totalPrice,
                'departure_date' => $request->departure_date,
                'image' => $post->image ? \App\Support\MediaManager::publicUrl($post->image) : asset('tourit/assets/img/tour/01.jpg'),
                'url' => route('frontend.products.show.legacy', $post->slug),
            ];
        }

        session()->put('cart', $cart);

        return redirect()->route('frontend.checkout.index');
    }

    public function remove(Request $request)
    {
        if ($request->cart_key) {
            $cart = session()->get('cart');
            if (isset($cart[$request->cart_key])) {
                unset($cart[$request->cart_key]);
                session()->put('cart', $cart);
            }
        }

        return redirect()->back()->with('success', 'Đã xóa tour khỏi giỏ hàng!');
    }
}
