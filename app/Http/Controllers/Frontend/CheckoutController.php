<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

use App\Models\Post;

class CheckoutController extends BaseFrontendController
{
    public function step1($id)
    {
        $product = Post::findOrFail($id);
        
        // Cần đảm bảo có thông tin giá hoặc lấy giá mặc định
        // Ở đây giả định giá người lớn lấy từ custom_price hoặc price
        
        return view('frontend.checkout.step1', $this->sharedViewData(compact('product')));
    }

    public function processStep1(Request $request, $id)
    {
        $product = Post::findOrFail($id);
        
        $request->validate([
            'departure_date' => 'required|string',
            'adult_quantity' => 'required|integer|min:1',
            'child_quantity' => 'nullable|integer|min:0',
            'infant_quantity' => 'nullable|integer|min:0',
        ]);

        $adultQty = (int) $request->input('adult_quantity', 1);
        $childQty = (int) $request->input('child_quantity', 0);
        $infantQty = (int) $request->input('infant_quantity', 0);
        
        $adultPrice = $product->custom_price ?: $product->price ?: 0;
        
        // Cập nhật giá dựa trên ngày khởi hành được chọn nếu có
        $selectedDate = $request->input('departure_date');
        $departurePrice = \App\Models\PostDeparturePrice::where('post_id', $product->id)
                            ->where('departure_date', $selectedDate)
                            ->first();
                            
        if ($departurePrice) {
            $rawPrice = $departurePrice->price ?: $adultPrice;
            $realPrice = $rawPrice > 0 ? ($rawPrice < 1000 ? $rawPrice * 1000000 : $rawPrice) : 0;
            if ($realPrice > 0) {
                $adultPrice = $realPrice;
            }
        }

        $childPrice = $product->child_price_percent ? ($adultPrice * $product->child_price_percent / 100) : 0;
        $infantPrice = $product->infant_price_percent ? ($adultPrice * $product->infant_price_percent / 100) : 0;

        $totalAdult = $adultPrice * $adultQty;
        $totalChild = $childPrice * $childQty;
        $totalInfant = $infantPrice * $infantQty;
        $totalPrice = $totalAdult + $totalChild + $totalInfant;

        $cartItem = [
            'post_id' => $product->id,
            'tour_name' => $product->title,
            'tour_code' => $product->tour_code ?: 'Đang cập nhật',
            'duration' => $product->duration ?: 'Đang cập nhật',
            'transport' => $product->transport ?: 'Đang cập nhật',
            'image' => $product->image ? \App\Support\MediaManager::publicUrl($product->image) : asset('tourit/assets/img/hotel/room/01.jpg'),
            'price' => $adultPrice,
            'adult_price' => $adultPrice,
            'child_price' => $childPrice,
            'infant_price' => $infantPrice,
            'adult_quantity' => $adultQty,
            'child_quantity' => $childQty,
            'infant_quantity' => $infantQty,
            'departure_date' => $request->input('departure_date'),
            'total' => $totalPrice,
        ];

        // Clear giỏ hàng cũ và set giỏ hàng mới chỉ có tour này
        session()->put('cart', [
            $product->id => $cartItem
        ]);

        return redirect()->route('frontend.checkout.index');
    }
    public function index()
    {
        $cart = session()->get('cart', []);
        if (empty($cart)) {
            return redirect()->route('frontend.cart.index')->with('error', 'Giỏ hàng của bạn đang trống.');
        }

        $total = array_sum(array_column($cart, 'total'));
        $user = auth()->user();

        return view('frontend.checkout.index', $this->sharedViewData(compact('cart', 'total', 'user')));
    }

    public function process(Request $request)
    {
        $cart = session()->get('cart', []);
        if (empty($cart)) {
            return redirect()->route('frontend.cart.index')->with('error', 'Giỏ hàng của bạn đang trống.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'required|email|max:255',
            'notes' => 'nullable|string',
        ]);

        $totalAmount = array_sum(array_column($cart, 'total'));

        DB::beginTransaction();

        try {
            $order = Order::create([
                'user_id' => auth()->id(),
                'order_code' => 'ORD-' . strtoupper(Str::random(8)),
                'total_amount' => $totalAmount,
                'status' => 'pending',
                'name' => $request->name,
                'phone' => $request->phone,
                'email' => $request->email,
                'notes' => $request->notes,
            ]);

            foreach ($cart as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'post_id' => $item['post_id'],
                    'tour_name' => $item['tour_name'],
                    'adult_quantity' => $item['adult_quantity'],
                    'child_quantity' => $item['child_quantity'],
                    'infant_quantity' => $item['infant_quantity'],
                    'price' => $item['price'],
                    'total' => $item['total'],
                    'departure_date' => $item['departure_date'],
                ]);
            }

            DB::commit();

            session()->forget('cart');

            return redirect()->route('frontend.checkout.success')->with('order_code', $order->order_code);

        } catch (\Exception $e) {
            DB::rollBack();
            \Illuminate\Support\Facades\Log::error('Order creation failed: ' . $e->getMessage() . ' - Trace: ' . $e->getTraceAsString());
            return redirect()->back()->with('error', 'Lỗi: ' . $e->getMessage())->withInput();
        }
    }

    public function success()
    {
        if (!session()->has('order_code')) {
            return redirect()->route('frontend.home');
        }

        return view('frontend.checkout.success', $this->sharedViewData());
    }
}
