<?php

namespace App\Http\Controllers\pages;

use App\Http\Controllers\Controller;
use App\Models\PostCategory;
use Illuminate\Http\Request;

class PostCategoryController extends Controller
{
  /**
   * Display a listing of the resource.
   */
  public function PostCategoryManagement()
  {
    return view('content.posts.category');
  }

  public function index(Request $request)
  {
    $columns = [
      1 => 'id',
      2 => 'name',
    ];

    $search = [];

    $totalData = PostCategory::count();

    $totalFiltered = $totalData;

    $limit = $request->input('length');
    $start = $request->input('start');
    $order = $columns[$request->input('order.0.column')];
    $dir = $request->input('order.0.dir');

    if (empty($request->input('search.value'))) {
      $postCategories = PostCategory::offset($start)
        ->limit($limit)
        ->orderBy($order, $dir)
        ->get();
    } else {
      $search = $request->input('search.value');

      $postCategories = PostCategory::where('id', 'LIKE', "%{$search}%")
        ->orWhere('name', 'LIKE', "%{$search}%")
        ->offset($start)
        ->limit($limit)
        ->orderBy($order, $dir)
        ->get();

      $totalFiltered = PostCategory::where('id', 'LIKE', "%{$search}%")
        ->orWhere('name', 'LIKE', "%{$search}%")
        ->count();
    }

    $data = [];

    if (!empty($postCategories)) {
      // providing a dummy id instead of database ids
      $ids = $start;

      foreach ($postCategories as $postCategory) {
        $nestedData['id'] = $postCategory->id;
        $nestedData['fake_id'] = ++$ids;
        $nestedData['name'] = $postCategory->name;

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
    $postCategoryID = $request->id;
    if ($postCategoryID) {
      // update the value
      $postCategory = PostCategory::updateOrCreate(['id' => $postCategoryID], ['name' => $request->name]);

      // user updated
      return response()->json(['message' => 'Updated']);
    } else {
      $categoryName = PostCategory::where('name', $request->name)->first();

      if (empty($categoryName)) {
        $categories = new PostCategory();
        $categories->name = $request->name;
        $categories->save();

        $categories = PostCategory::pluck('name', 'id')->all();
        // user created
        return response()->json(['success' => 'true', 'categories' => $categories, 'message' => 'created']);
      } else {
        // user already exist
        return response()->json(['message' => 'already exits'], 422);
      }
    }
  }

  /**
   * Display the specified resource.
   */
  public function show(PostCategory $postCategory)
  {
    //
  }

  /**
   * Show the form for editing the specified resource.
   */
  public function edit($id)
  {
    $where = ['id' => $id];

    $postCategory = PostCategory::where($where)->first();

    return response()->json($postCategory);
  }

  /**
   * Update the specified resource in storage.
   */
  public function update(Request $request, PostCategory $postCategory)
  {
    //
  }

  /**
   * Remove the specified resource from storage.
   */
  public function destroy($id)
  {
    $postCategory = PostCategory::where('id', $id)->delete();
  }

  public function get()
  {
    $postCategory = PostCategory::all();
    if ($postCategory) {
      return response()->json([
        'code' => 200,
        'data' => $postCategory,
      ]);
    } else {
      return response()->json([
        'message' => 'Internal Server Error',
        'code' => 500,
        'data' => [],
      ]);
    }
  }
}
