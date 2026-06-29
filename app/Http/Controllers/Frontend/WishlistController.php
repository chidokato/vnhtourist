<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;

class WishlistController extends BaseFrontendController
{
    /**
     * Display the wishlist page.
     */
    public function index()
    {
        $wishlistIds = session()->get('wishlist', []);
        
        // Ensure we only query the IDs
        $wishlistIds = array_keys(array_filter($wishlistIds));

        $products = Post::where('type', Post::TYPE_PRODUCT)
            ->whereIn('id', $wishlistIds)
            ->where('is_active', true)
            ->paginate(12);

        return view('frontend.wishlist.index', $this->sharedViewData(compact('products')));
    }

    /**
     * Toggle a product in the wishlist.
     */
    public function toggle(Request $request)
    {
        $productId = $request->input('product_id');
        
        if (!$productId) {
            return response()->json(['success' => false, 'message' => 'Mã sản phẩm không hợp lệ.'], 400);
        }

        $wishlist = session()->get('wishlist', []);
        
        $isAdded = false;
        if (isset($wishlist[$productId])) {
            // Remove
            unset($wishlist[$productId]);
            $message = 'Đã xoá khỏi danh sách yêu thích.';
        } else {
            // Add
            $wishlist[$productId] = true;
            $isAdded = true;
            $message = 'Đã thêm vào danh sách yêu thích.';
        }

        session()->put('wishlist', $wishlist);
        
        // Count total valid items
        $count = count(array_filter($wishlist));

        return response()->json([
            'success' => true, 
            'is_added' => $isAdded,
            'message' => $message,
            'count' => $count
        ]);
    }
}
