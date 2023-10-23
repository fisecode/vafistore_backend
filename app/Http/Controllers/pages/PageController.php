<?php

namespace App\Http\Controllers\pages;

use App\Http\Controllers\Controller;
use App\Models\Page;
use App\Traits\ImageStorage;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class PageController extends Controller
{
  use ImageStorage;
  public function PageManagement()
  {
    return view('content.content-pages.index');
  }
  /**
   * Display a listing of the resource.
   */
  public function index(Request $request)
  {
    $columns = [
      1 => 'id',
      2 => 'page_name',
      3 => 'status',
    ];

    $search = [];

    $totalData = Page::count();

    $totalFiltered = $totalData;

    $limit = $request->input('length');
    $start = $request->input('start');
    $order = $columns[$request->input('order.0.column')];
    $dir = $request->input('order.0.dir');

    if (empty($request->input('search.value'))) {
      $pages = Page::offset($start)
        ->limit($limit)
        ->orderBy($order, $dir)
        ->get();
    } else {
      $search = $request->input('search.value');

      $pages = Page::where('id', 'LIKE', "%{$search}%")
        ->orWhere('page_name', 'LIKE', "%{$search}%")
        ->offset($start)
        ->limit($limit)
        ->orderBy($order, $dir)
        ->get();

      $totalFiltered = Page::where('id', 'LIKE', "%{$search}%")
        ->orWhere('page_name', 'LIKE', "%{$search}%")
        ->count();
    }

    $data = [];

    if (!empty($pages)) {
      // providing a dummy id instead of database ids
      $ids = $start;

      foreach ($pages as $page) {
        $nestedData['id'] = $page->id;
        $nestedData['fake_id'] = ++$ids;
        $nestedData['name'] = $page->page_name;
        $nestedData['status'] = $page->status;

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
    return view('content.content-pages.form');
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

    $pageId = $request->id;
    $title = $request->title;
    $page = Page::find($pageId);
    $pageStatus = 1;
    $slug = Str::slug($title);

    if ($page) {
      // Handle image upload
      $imagePath = $page->image;
      if ($request->hasFile('image')) {
        $image = $request->file('image');
        $imagePath = $this->uploadImage($image, $request->title, 'pages', true, $imagePath);
      }

      // Handle image renaming
      $newTitle = $title;
      $oldTitle = $page->title;
      if ($oldTitle !== $newTitle) {
        $newImageName = $this->renameImage($imagePath, $request->title, 'pages');
        $imagePath = $newImageName;
      }
      $pageStatus = $page->status;
      $page->page_name = $request->title;
      $page->slug = $slug;
      $page->content = $request->content;
      $page->image = $imagePath;
      $page->status = $pageStatus;
      $page->save();

      if ($page) {
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

      $page = Page::create([
        'page_name' => $title,
        'slug' => $slug,
        'content' => $request->content,
        'image' => $imagePath,
        'status' => $pageStatus,
        'user_id' => Auth::user()->id,
      ]);

      if ($page) {
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
  public function show(Page $page)
  {
    //
  }

  /**
   * Show the form for editing the specified resource.
   */
  public function edit($id)
  {
    $page = Page::find($id);

    if (!$page) {
      // Handle jika posting tidak ditemukan
      return redirect()
        ->route('page')
        ->with('error', 'Post not found.');
    }
    return view('content.content-pages.form', compact('page'));
  }

  /**
   * Update the specified resource in storage.
   */
  public function update(Request $request, $id)
  {
    $newStatus = $request->input('newStatus');

    $page = Page::find($id);
    if ($page) {
      $page->status = $newStatus;
      $page->save();
      // page updated
      if ($newStatus == 0) {
        return response()->json(['title' => 'Successfully deactivated!', 'message' => 'Page deactivated successfully']);
      } else {
        return response()->json(['title' => 'Successfully activated!', 'message' => 'Page activated successfully']);
      }
    } else {
      // page not found
      return response()->json(['title' => 'Failed', 'message' => 'Page Not Found'], 422);
    }
  }

  /**
   * Remove the specified resource from storage.
   */
  public function destroy($id)
  {
    $page = Page::find($id);
    $image = $page->image;

    if ($image) {
      $this->deleteImage($image, 'pages');
    }
    $page->delete();
  }
}
