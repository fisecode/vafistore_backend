<?php

namespace App\Http\Controllers\product;

use App\Http\Controllers\Controller;
use App\Models\ProductCategory;
use App\Traits\ImageStorage;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ProductCategoryController extends Controller
{
  use ImageStorage;
  public function ProductCategoryManagement()
  {
    return view('content.product.category.index');
  }
  /**
   * Display a listing of the resource.
   */
  public function index(Request $request)
  {
    $columns = [
      1 => 'id',
      2 => 'name',
      3 => 'popular',
      4 => 'sort',
      5 => 'status',
    ];

    $search = [];

    $totalData = ProductCategory::count();

    $totalFiltered = $totalData;

    $limit = $request->input('length');
    $start = $request->input('start');
    $order = $columns[$request->input('order.0.column')];
    $dir = $request->input('order.0.dir');

    if (empty($request->input('search.value'))) {
      $productCategories = ProductCategory::offset($start)
        ->limit($limit)
        ->orderBy($order, $dir)
        ->get();
    } else {
      $search = $request->input('search.value');

      $productCategories = ProductCategory::where('id', 'LIKE', "%{$search}%")
        ->orWhere('name', 'LIKE', "%{$search}%")
        ->orWhere('popular', 'LIKE', "%{$search}%")
        ->orWhere('sort', 'LIKE', "%{$search}%")
        ->orWhere('status', 'LIKE', "%{$search}%")
        ->offset($start)
        ->limit($limit)
        ->orderBy($order, $dir)
        ->get();

      $totalFiltered = ProductCategory::where('id', 'LIKE', "%{$search}%")
        ->orWhere('name', 'LIKE', "%{$search}%")
        ->orWhere('popular', 'LIKE', "%{$search}%")
        ->orWhere('sort', 'LIKE', "%{$search}%")
        ->orWhere('status', 'LIKE', "%{$search}%")
        ->count();
    }

    $data = [];

    if (!empty($productCategories)) {
      // providing a dummy id instead of database ids
      $ids = $start;

      foreach ($productCategories as $productCategory) {
        $nestedData['id'] = $productCategory->id;
        $nestedData['fake_id'] = ++$ids;
        $nestedData['name'] = $productCategory->name;
        $nestedData['image'] = $productCategory->image;
        $nestedData['popular'] = $productCategory->popular;
        $nestedData['sort'] = $productCategory->sort;
        $nestedData['status'] = $productCategory->status;

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
    return view('content.product.category.form');
  }

  /**
   * Store a newly created resource in storage.
   */
  public function store(Request $request)
  {
    // Validation rules
    $rules = [
      'title' => 'required',
      'content' => 'required',
      'image' => 'image|mimes:jpeg,png,jpg|max:800',
    ];

    // Validate the request data
    $validator = Validator::make($request->all(), $rules);

    if ($validator->fails()) {
      return redirect()
        ->back()
        ->withInput()
        ->withErrors($validator)
        ->with('error', $validator->messages()->first());
    }

    $productCategoryId = $request->id;
    $title = $request->title;
    $productCategory = ProductCategory::find($productCategoryId);
    $productCategoriestatus = 1;
    $slug = ProductCategory::slug($title);

    if ($productCategory) {
      // Handle image upload
      $imagePath = $productCategory->image;
      if ($request->hasFile('image')) {
        $image = $request->file('image');
        $imagePath = $this->uploadImage($image, $request->title, 'pages', true, $imagePath);
      }

      // Handle image renaming
      $newTitle = $title;
      $oldTitle = $productCategory->title;
      if ($oldTitle !== $newTitle) {
        $newImageName = $this->renameImage($imagePath, $request->title, 'pages');
        $imagePath = $newImageName;
      }
      $productCategoriestatus = $productCategory->status;
      $productCategory->page_name = $request->title;
      $productCategory->slug = $slug;
      $productCategory->content = $request->content;
      $productCategory->image = $imagePath;
      $productCategory->status = $productCategoriestatus;
      $productCategory->save();

      if ($productCategory) {
        $message = 'Well Done! Content Page Saved!';
        return redirect()
          ->route('page')
          ->with('success', $message);
      } else {
        return redirect()
          ->route('page')
          ->with('error', 'An error occurred while saving the page.');
      }
    } else {
      // Handle file upload
      $imagePath = null;
      if ($request->hasFile('image')) {
        $image = $request->file('image');
        $imagePath = $this->uploadImage($image, $title, 'pages');
      } else {
        $imagePath = 'no-photo.jpg'; // Default image path if no file is uploaded
      }

      $productCategory = ProductCategory::create([
        'page_name' => $title,
        'slug' => $slug,
        'content' => $request->content,
        'image' => $imagePath,
        'status' => $productCategoriestatus,
        'user_id' => Auth::user()->id,
      ]);

      if ($productCategory) {
        $message = 'Well Done! Content Page Saved!';
        return redirect()
          ->route('page')
          ->with('success', $message);
      } else {
        return redirect()
          ->route('page')
          ->with('error', 'An error occurred while saving the page.');
      }
    }
  }

  /**
   * Display the specified resource.
   */
  public function show(Page $productCategory)
  {
    //
  }

  /**
   * Show the form for editing the specified resource.
   */
  public function edit($id)
  {
    $productCategory = ProductCategory::find($id);

    if (!$productCategory) {
      // Handle jika posting tidak ditemukan
      return redirect()
        ->route('category-product')
        ->with('error', 'Product not found.');
    }
    return view('content.product.category.form', compact('productCategory'));
  }

  /**
   * Update the specified resource in storage.
   */
  public function update(Request $request, $id)
  {
    $newStatus = $request->input('newStatus');

    $productCategory = ProductCategory::find($id);
    if ($productCategory) {
      $productCategory->status = $newStatus;
      $productCategory->save();
      // page updated
      if ($newStatus == 0) {
        return response()->json([
          'title' => 'Successfully deactivated!',
          'message' => 'Product deactivated successfully',
        ]);
      } else {
        return response()->json(['title' => 'Successfully activated!', 'message' => 'Product activated successfully']);
      }
    } else {
      // page not found
      return response()->json(['title' => 'Failed', 'message' => 'Product Not Found'], 422);
    }
  }

  /**
   * Remove the specified resource from storage.
   */
  public function destroy($id)
  {
    $productCategory = ProductCategory::find($id);
    $image = $productCategory->image;

    if ($image) {
      $this->deleteImage($image, 'pages');
    }
    $productCategory->delete();
  }
}
