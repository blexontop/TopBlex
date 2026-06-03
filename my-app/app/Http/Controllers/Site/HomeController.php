<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Schema;

class HomeController extends Controller
{
    public function index()
    {
        $products = collect();

        if (Schema::hasTable('products')) {
            try {
                $products = Product::where('is_visible', true)->latest()->take(12)->get();
            } catch (QueryException $e) {
                $products = collect();
            }
        }

        return view('home', compact('products'));
    }
}
