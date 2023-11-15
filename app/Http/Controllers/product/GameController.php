<?php

namespace App\Http\Controllers\product;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class GameController extends Controller
{
  /**
   * Display a listing of the resource.
   */
  public function GameManagement()
  {
    return view('content.product.game.index');
  }

  /**
   * Display a listing of the resource.
   */
  public function index(Request $request)
  {
    $columns = [
      1 => 'id',
      2 => 'image',
      3 => 'code',
      4 => 'title',
      5 => 'kategori',
      6 => 'harga_modal',
      7 => 'harga_jual',
      8 => 'harga_reseller',
      9 => 'jenis',
      10 => 'status',
    ];

    $search = [];

    $totalData = Product::count();

    $totalFiltered = $totalData;

    $limit = $request->input('length');
    $start = $request->input('start');
    $order = $columns[$request->input('order.0.column')];
    $dir = $request->input('order.0.dir');

    if (empty($request->input('search.value'))) {
      $products = Product::offset($start)
        ->limit($limit)
        ->orderBy($order, $dir)
        ->get();
    } else {
      $search = $request->input('search.value');

      $products = Product::where('id', 'LIKE', "%{$search}%")
        ->orWhere('image', 'LIKE', "%{$search}%")
        ->orWhere('code', 'LIKE', "%{$search}%")
        ->orWhere('title', 'LIKE', "%{$search}%")
        ->orWhere('kategori', 'LIKE', "%{$search}%")
        ->orWhere('harga_modal', 'LIKE', "%{$search}%")
        ->orWhere('harga_jual', 'LIKE', "%{$search}%")
        ->orWhere('harga_reseller', 'LIKE', "%{$search}%")
        ->orWhere('jenis', 'LIKE', "%{$search}%")
        ->orWhere('status', 'LIKE', "%{$search}%")
        ->offset($start)
        ->limit($limit)
        ->orderBy($order, $dir)
        ->get();

      $totalFiltered = Product::where('id', 'LIKE', "%{$search}%")
        ->orWhere('image', 'LIKE', "%{$search}%")
        ->orWhere('code', 'LIKE', "%{$search}%")
        ->orWhere('title', 'LIKE', "%{$search}%")
        ->orWhere('kategori', 'LIKE', "%{$search}%")
        ->orWhere('harga_modal', 'LIKE', "%{$search}%")
        ->orWhere('harga_jual', 'LIKE', "%{$search}%")
        ->orWhere('harga_reseller', 'LIKE', "%{$search}%")
        ->orWhere('jenis', 'LIKE', "%{$search}%")
        ->orWhere('status', 'LIKE', "%{$search}%")
        ->count();
    }

    $data = [];

    if (!empty($products)) {
      // providing a dummy id instead of database ids
      $ids = $start;

      foreach ($products as $product) {
        if ($product->type == 'Umum' || $product->type == 'Membership') {
          $nestedData['id'] = $product->id;
          $nestedData['fake_id'] = ++$ids;
          $nestedData['image'] = $product->image;
          $nestedData['code'] = $product->code;
          $nestedData['item'] = $product->title;
          $nestedData['category'] = $product->kategori;
          $nestedData['capital'] = $product->harga_modal;
          $nestedData['selling'] = $product->harga_jual;
          $nestedData['reseller'] = $product->harga_reseller;
          $nestedData['provider'] = $product->jenis;
          $nestedData['status'] = $product->status;

          $data[] = $nestedData;
        }
      }
    }

    if ($data) {
      return response()->json([
        'draw' => intval($request->input('draw')),
        'recordsTotal' => intval($totalData),
        'recordsFiltered' => intval($totalFiltered),
        'code' => 200,
        'data' => $data,
      ]);
    } else {
      return response()->json([
        'message' => 'Internal Server Error',
        'code' => 500,
        'data' => [],
      ]);
    }
  }

  /**
   * Show the form for creating a new resource.
   */
  public function create()
  {
    //
  }

  /**
   * Store a newly created resource in storage.
   */
  public function store(Request $request)
  {
    //
  }

  /**
   * Display the specified resource.
   */
  public function show(Product $product)
  {
    //
  }

  /**
   * Show the form for editing the specified resource.
   */
  public function edit(Product $product)
  {
    //
  }

  /**
   * Update the specified resource in storage.
   */
  public function update(Request $request, Product $product)
  {
    //
  }

  /**
   * Remove the specified resource from storage.
   */
  public function destroy(Product $product)
  {
    //
  }
}
