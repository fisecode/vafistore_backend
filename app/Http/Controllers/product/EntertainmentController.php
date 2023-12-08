<?php

namespace App\Http\Controllers\product;

use App\Http\Controllers\Controller;
use App\Models\EntertainmentProduct as Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class EntertainmentController  extends Controller
{
  public function EntertainmentManagement()
  {
    $brands = Product::distinct()->pluck('brand');
    $categories = Product::distinct()->pluck('category');
    return view('content.product.entertainment.index', compact('brands', 'categories'));
  }

  /**
   * Display a listing of the resource.
   */
  public function index(Request $request)
  {
    $columns = [
      1 => 'id',
      2 => 'item',
      3 => 'brand',
      4 => 'capital_price',
      5 => 'selling_price',
      6 => 'reseller_price',
      7 => 'category',
      8 => 'provider',
      9 => 'status',
    ];

    $search = [];

    $totalData = Product::count();
    $query = Product::select('*');

    $totalFiltered = $totalData;

    $limit = $request->input('length');
    $start = $request->input('start');
    $order = $columns[$request->input('order.0.column')];
    $dir = $request->input('order.0.dir');

    if ($request->status == '0' || $request->status == '1') {
      $query = $query->where('status', $request->status);
      $totalFiltered = $query->count();
    }

    if (!empty($request->provider)) {
      $query = $query->Where('provider', $request->provider);
      $totalFiltered = $query->count();
    }
    if (!empty($request->brand)) {
      $query = $query->Where('brand', $request->brand);
      $totalFiltered = $query->count();
    }

    if (!empty($request->category)) {
      $query = $query->Where('category', $request->category);
      $totalFiltered = $query->count();
    }

    if (!empty($request->input('search.value'))) {
      $search = $request->input('search.value');

      $query
        ->where('id', 'LIKE', "%{$search}%")
        ->orWhere('image', 'LIKE', "%{$search}%")
        ->orWhere('code', 'LIKE', "%{$search}%")
        ->orWhere('item', 'LIKE', "%{$search}%")
        ->orWhere('brand', 'LIKE', "%{$search}%")
        ->orWhere('capital_price', 'LIKE', "%{$search}%")
        ->orWhere('selling_price', 'LIKE', "%{$search}%")
        ->orWhere('reseller_price', 'LIKE', "%{$search}%")
        ->orWhere('category', 'LIKE', "%{$search}%");
      $totalFiltered = $query->count();
    }

    $products = $query
      ->offset($start)
      ->limit($limit)
      ->orderBy($order, $dir)
      ->get();

    $data = [];

    if (!empty($products)) {
      // providing a dummy id instead of database ids
      $ids = $start;

      foreach ($products as $product) {
        $nestedData['id'] = $product->id;
        $nestedData['fake_id'] = ++$ids;
        $nestedData['image'] = $product->image;
        $nestedData['code'] = $product->code;
        $nestedData['item'] = $product->item;
        $nestedData['brand'] = $product->brand;
        $nestedData['capital'] = $product->capital_price;
        $nestedData['selling'] = $product->selling_price;
        $nestedData['reseller'] = $product->reseller_price;
        $nestedData['category'] = $product->category;
        $nestedData['provider'] = $product->provider;
        $nestedData['status'] = $product->status;

        $data[] = $nestedData;
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
    // Validation rules
    $rules = [
      'item' => 'required',
      'category' => 'required',
      'selling' => 'numeric|required',
      'reseller' => 'numeric|required',
    ];

    // Validate the request data
    $validator = Validator::make($request->all(), $rules);

    if ($validator->fails()) {
      return response()->json(['title' => 'Error', 'message' => $validator->messages()->first()]);
    }

    $productId = $request->id;
    $item = $request->item;
    $category = $request->category;
    $selling = $request->selling;
    $reseller = $request->reseller;

    // Find the product
    $product = Product::find($productId);

    if (!$product) {
      return response()->json(['title' => 'Error', 'message' => 'Product not found.']);
    }

    // Update the product
    $product->item = $item;
    $product->category = $category;
    $product->selling_price = $selling;
    $product->reseller_price = $reseller;

    if ($product->update()) {
      return response()->json(['title' => 'Well Done!', 'message' => 'Product Updated!']);
    } else {
      return response()->json(['title' => 'Error', 'message' => 'An error occurred while updating the product.']);
    }
  }

  /**
   * Display the specified resource.
   */
  public function show(PrepaidProduct $prepaidProduct)
  {
    //
  }

  /**
   * Show the form for editing the specified resource.
   */
  public function edit($id)
  {
    $where = ['id' => $id];

    $product = Product::where($where)->first();

    return response()->json($product);
  }

  /**
   * Update the specified resource in storage.
   */
  public function update(Request $request, $id)
  {
    $newStatus = $request->input('newStatus');

    $product = Product::find($id);
    if ($product) {
      $product->status = $newStatus;
      $product->save();
      // slide updated
      if ($newStatus == 0) {
        return response()->json([
          'title' => 'Successfully deactivated!',
          'message' => "Product {$product->code} deactivated successfully",
        ]);
      } else {
        return response()->json([
          'title' => 'Successfully activated!',
          'message' => "Product {$product->code} activated successfully",
        ]);
      }
    } else {
      // slide not found
      return response()->json(['title' => 'Failed', 'message' => 'Product Not Found'], 422);
    }
  }

  /**
   * Remove the specified resource from storage.
   */
  public function destroy(PrepaidProduct $prepaidProduct)
  {
    //
  }

  public function saveBulkEdit(Request $request)
  {
    try {
      // Ambil data dari permintaan
      $selectedIds = $request->input('ids');
      $bulkCategory = $request->input('bulkCategory');
      $bulkStatus = $request->input('bulkStatus');
      // Tambahan data lainnya sesuai kebutuhan

      // Ambil nilai yang ada untuk kolom-kolom yang tidak diisi
      $existingData = Product::whereIn('id', explode(',', $selectedIds))->get();

      // Perbarui kolom-kolom yang tidak diisi dengan nilai yang ada
      // Lakukan operasi penyimpanan atau pembaruan data

      // Perbarui kolom-kolom yang tidak diisi dengan nilai yang ada
      foreach ($existingData as $data) {
        $updateData = [];
        if (!empty($bulkCategory)) {
          $updateData['category'] = $bulkCategory;
        } else {
          $updateData['category'] = $data->category;
        }
        if ($bulkStatus !== null) {
          $updateData['status'] = $bulkStatus;
        } else {
          $updateData['status'] = $data->status;
        }
        // Perbarui kolom-kolom lainnya sesuai kebutuhan
        // ...

        // Hanya lakukan pembaruan jika ada data yang perlu diperbarui
        if (!empty($updateData)) {
          Product::where('id', $data->id)->update($updateData);
        }
      }

      // Response sukses jika penyimpanan berhasil
      return response()->json(['message' => 'Data berhasil disimpan'], 200);
    } catch (\Exception $e) {
      // Response error jika terjadi kesalahan
      return response()->json(['message' => 'Gagal menyimpan data'], 500);
    }
  }
}
