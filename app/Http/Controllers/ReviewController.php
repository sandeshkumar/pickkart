<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function store(Request $request, Product $product)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'title' => 'nullable|string|max:255',
            'body' => 'required|string|max:2000',
        ]);

        $existing = $product->reviews()->where('user_id', auth()->id())->first();
        if ($existing) {
            return redirect()->back()->with('error', 'You have already reviewed this product.');
        }

        $product->reviews()->create([
            'user_id' => auth()->id(),
            'rating' => $request->rating,
            'title' => $request->title,
            'body' => $request->body,
            'status' => 'pending',
        ]);

        return redirect()->back()->with('success', 'Thank you! Your review has been submitted and is pending approval.');
    }
}
