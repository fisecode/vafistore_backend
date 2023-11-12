<?php

namespace App\Http\Controllers\socials;

use App\Http\Controllers\Controller;
use App\Models\Social;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SocialController extends Controller
{
  public function SocialManagement()
  {
    return view('content.socials.index');
  }
  /**
   * Display a listing of the resource.
   */
  public function index(Request $request)
  {
    $columns = [
      1 => 'id',
      2 => 'name',
      3 => 'url',
      4 => 'icon',
      5 => 'status',
    ];

    $search = [];

    $totalData = Social::count();

    $totalFiltered = $totalData;

    $limit = $request->input('length');
    $start = $request->input('start');
    $order = $columns[$request->input('order.0.column')];
    $dir = $request->input('order.0.dir');

    if (empty($request->input('search.value'))) {
      $socials = Social::offset($start)
        ->limit($limit)
        ->orderBy($order, $dir)
        ->get();
    } else {
      $search = $request->input('search.value');

      $socials = Social::where('id', 'LIKE', "%{$search}%")
        ->orWhere('name', 'LIKE', "%{$search}%")
        ->orWhere('url', 'LIKE', "%{$search}%")
        ->orWhere('icon', 'LIKE', "%{$search}%")
        ->offset($start)
        ->limit($limit)
        ->orderBy($order, $dir)
        ->get();

      $totalFiltered = Social::where('id', 'LIKE', "%{$search}%")
        ->orWhere('name', 'LIKE', "%{$search}%")
        ->orWhere('url', 'LIKE', "%{$search}%")
        ->orWhere('icon', 'LIKE', "%{$search}%")
        ->count();
    }

    $data = [];

    if (!empty($socials)) {
      // providing a dummy id instead of database ids
      $ids = $start;

      foreach ($socials as $social) {
        $nestedData['id'] = $social->id;
        $nestedData['fake_id'] = ++$ids;
        $nestedData['name'] = $social->name;
        $nestedData['url'] = $social->url;
        $nestedData['icon'] = $social->icon;
        $nestedData['status'] = $social->status;

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
      'name' => 'required',
      'url' => 'required',
      'icon' => 'required',
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

    $socialId = $request->id;
    $name = $request->name;
    $social = Social::find($socialId);
    $socialStatus = 1;

    if ($social) {
      $socialStatus = $social->status;
      $social->name = $name;
      $social->url = $request->url;
      $social->icon = $request->icon;
      $social->status = $socialStatus;
      $social->save();

      if ($social) {
        return response()->json(['title' => 'Well Done!', 'message' => 'Social Media Saved!']);
      } else {
        return response()->json(['title' => 'Error', 'message' => 'An error occurred while saving the Social Media.']);
      }
    } else {
      $social = Social::create([
        'name' => $name,
        'url' => $request->url,
        'icon' => $request->icon,
        'status' => $socialStatus,
      ]);

      if ($social) {
        return response()->json(['title' => 'Well Done!', 'message' => 'Social Media Saved!']);
      } else {
        return response()->json(['title' => 'Error', 'message' => 'An error occurred while saving the Social Media.']);
      }
    }
  }

  /**
   * Display the specified resource.
   */
  public function show(Social $social)
  {
    //
  }

  /**
   * Show the form for editing the specified resource.
   */
  public function edit($id)
  {
    $where = ['id' => $id];

    $social = Social::where($where)->first();

    return response()->json($social);
  }

  /**
   * Update the specified resource in storage.
   */
  public function update(Request $request, $id)
  {
    $newStatus = $request->input('newStatus');

    $social = Social::find($id);
    if ($social) {
      $social->status = $newStatus;
      $social->save();
      // slide updated
      if ($newStatus == 0) {
        return response()->json([
          'title' => 'Successfully deactivated!',
          'message' => 'Social media deactivated successfully',
        ]);
      } else {
        return response()->json([
          'title' => 'Successfully activated!',
          'message' => 'Social media activated successfully',
        ]);
      }
    } else {
      // slide not found
      return response()->json(['title' => 'Failed', 'message' => 'Social Media Not Found'], 422);
    }
  }

  /**
   * Remove the specified resource from storage.
   */
  public function destroy($id)
  {
    $slide = Social::find($id);
    $slide->delete();
  }
}
