<?php

use Faker\Generator;
use Illuminate\Container\Container;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/products', function () {
    return view('welcome');
});

Route::get('/', function () {
    $products = \App\Models\Product::all()->sortBy(['created_at', 'updated_at']);

    return response()->view('products', ['products' => $products], 200);
});

Route::get('/products-cache', function () {
    $products = Cache::get('products');
    $statusCode = 304;
    if (empty($products)) {
        $products = \App\Models\Product::all()->sortBy(['created_at', 'updated_at']);
        Cache::put('products', $products, 5);
        $statusCode = 200;
    }

    return response()->view('products', ['products' => $products], $statusCode);
});

Route::get('/products-cache-stampede', function () {
    $productsTtl = Cache::get('products-ttl');
    $statusCode = 304;
    if (!$productsTtl || $productsTtl < time()) {
        Cache::put('products-ttl', strtotime("+5 seconds"), 10);
        $products = \App\Models\Product::all()->sortBy(['created_at', 'updated_at']);
        Cache::put('products', $products, 10);
        $statusCode = 200;
    } else {
        $products = Cache::get('products');
    }

    return response()->view('products', ['products' => $products], $statusCode);
});

Route::get('/products-cache-exp', function () {
    $productsTtl = Cache::get('products-ttl');
    $statusCode = 304;
    $exp = random_int(30, 100) * exp(0.05 / ($productsTtl - time() + 0.1)) / 100;
    if (!$productsTtl || $exp > 1) {
        Cache::put('products-ttl', strtotime("+5 seconds"), 10);
        $products = \App\Models\Product::all()->sortBy(['created_at', 'price', 'updated_at']);
        Cache::put('products', $products, 10);
        $statusCode = 200;
    } else {
        $products = Cache::get('products');
    }

    return response()->view('products', ['products' => $products], $statusCode);
});
