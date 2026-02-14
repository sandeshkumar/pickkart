<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Page;
use App\Models\Product;

class SitemapController extends Controller
{
    public function index()
    {
        return response()
            ->view('sitemap.index')
            ->header('Content-Type', 'application/xml');
    }

    public function products()
    {
        $products = Product::active()
            ->select('slug', 'updated_at')
            ->orderBy('updated_at', 'desc')
            ->get();

        return response()
            ->view('sitemap.products', compact('products'))
            ->header('Content-Type', 'application/xml');
    }

    public function categories()
    {
        $categories = Category::active()
            ->select('slug', 'updated_at')
            ->orderBy('updated_at', 'desc')
            ->get();

        return response()
            ->view('sitemap.categories', compact('categories'))
            ->header('Content-Type', 'application/xml');
    }

    public function pages()
    {
        $pages = Page::active()
            ->select('slug', 'updated_at')
            ->orderBy('updated_at', 'desc')
            ->get();

        return response()
            ->view('sitemap.pages', compact('pages'))
            ->header('Content-Type', 'application/xml');
    }
}
