<?php

namespace App\Http\Controllers\pages;

use App\Http\Controllers\Controller;
use App\Models\Slide;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Traits\ImageStorage;
use Illuminate\Http\Request;

class SlideController extends Controller
{
  use ImageStorage;
  public function SlideManagement()
  {
    return view('content.slide.index');
  }
  /**
   * Display a listing of the resource.
   */
  public function index(Request $request)
  {
    $columns = [
      1 => 'id',
      2 => 'image',
      3 => 'description',
      4 => 'status',
      5 => 'sort',
    ];

    $search = [];

    $totalData = Slide::count();

    $totalFiltered = $totalData;

    $limit = $request->input('length');
    $start = $request->input('start');
    $order = $columns[$request->input('order.0.column')];
    $dir = $request->input('order.0.dir');

    if (empty($request->input('search.value'))) {
      $slides = Slide::offset($start)
        ->limit($limit)
        ->orderBy($order, $dir)
        ->get();
    } else {
      $search = $request->input('search.value');

      $slides = Slide::where('id', 'LIKE', "%{$search}%")
        ->orWhere('image', 'LIKE', "%{$search}%")
        ->orWhere('description', 'LIKE', "%{$search}%")
        ->orWhere('sort', 'LIKE', "%{$search}%")
        ->offset($start)
        ->limit($limit)
        ->orderBy($order, $dir)
        ->get();

      $totalFiltered = Slide::where('id', 'LIKE', "%{$search}%")
        ->orWhere('image', 'LIKE', "%{$search}%")
        ->orWhere('description', 'LIKE', "%{$search}%")
        ->orWhere('sort', 'LIKE', "%{$search}%")
        ->count();
    }

    $data = [];

    if (!empty($slides)) {
      // providing a dummy id instead of database ids
      $ids = $start;

      foreach ($slides as $slide) {
        $nestedData['id'] = $slide->id;
        $nestedData['image'] = $slide->image;
        $nestedData['description'] = $slide->description;
        $nestedData['sort'] = $slide->sort;
        $nestedData['status'] = $slide->status;

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
      'description' => 'required',
      'sortOrder' => 'required',
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

    $slideId = $request->id;
    $description = $request->description;
    $slide = Slide::find($slideId);
    $slideStatus = 1;

    if ($slide) {
      // Handle image upload
      $imagePath = $slide->image;
      if ($request->hasFile('image')) {
        $image = $request->file('image');
        $imagePath = $this->uploadImage($image, $request->description, 'slides', true, $imagePath);
      }

      // Handle image renaming
      $newTitle = $request->description;
      $oldTitle = $slide->title;
      if ($oldTitle !== $newTitle) {
        $newImageName = $this->renameImage($imagePath, $newTitle, 'slides');
        $imagePath = $newImageName;
      }

      $slideStatus = $slide->status;
      $slide->description = $description;
      $slide->image = $imagePath;
      $slide->sort = $request->sortOrder;
      $slide->status = $slideStatus;
      $slide->save();

      return response()->json(['title' => 'Well Done!', 'message' => 'Content Slide Saved!']);

      if ($slide) {
        return response()->json(['title' => 'Well Done!', 'message' => 'Content Slide Saved!']);
      } else {
        return response()->json(['title' => 'Error', 'message' => 'An error occurred while saving the slide.']);
      }
    } else {
      // Handle file upload
      $imagePath = null;
      if ($request->hasFile('image')) {
        $image = $request->file('image');
        $imagePath = $this->uploadImage($image, $description, 'slides');
      } else {
        $imagePath = 'no-photo.jpg'; // Default image path if no file is uploaded
      }

      $slide = Slide::create([
        'description' => $description,
        'image' => $imagePath,
        'sort' => $request->sortOrder,
        'status' => $slideStatus,
        'user_id' => Auth::user()->id,
      ]);

      if ($slide) {
        $message = 'Well Done! Content Slide Saved!';
        return redirect()
          ->route('slide')
          ->with('success', $message);
      } else {
        return redirect()
          ->route('slide')
          ->with('error', 'An error occurred while saving the slide.');
      }
    }
  }

  /**
   * Display the specified resource.
   */
  public function show(Slide $slide)
  {
    //
  }

  /**
   * Show the form for editing the specified resource.
   */
  public function edit($id)
  {
    $where = ['id' => $id];

    $slide = Slide::where($where)->first();

    return response()->json($slide);
  }

  /**
   * Update the specified resource in storage.
   */
  public function update(Request $request, $id)
  {
    $newStatus = $request->input('newStatus');

    $slide = Slide::find($id);
    if ($slide) {
      $slide->status = $newStatus;
      $slide->save();
      // slide updated
      if ($newStatus == 0) {
        return response()->json([
          'title' => 'Successfully deactivated!',
          'message' => 'Slide deactivated successfully',
        ]);
      } else {
        return response()->json(['title' => 'Successfully activated!', 'message' => 'Slide activated successfully']);
      }
    } else {
      // slide not found
      return response()->json(['title' => 'Failed', 'message' => 'Slide Not Found'], 422);
    }
  }

  /**
   * Remove the specified resource from storage.
   */
  public function destroy($id)
  {
    $slide = Slide::find($id);
    $image = $slide->image;

    if ($image) {
      $this->deleteImage($image, 'slides');
    }
    $slide->delete();
  }
}
