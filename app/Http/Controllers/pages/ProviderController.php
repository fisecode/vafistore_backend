<?php

namespace App\Http\Controllers\pages;

use App\Models\ApiManagement as Provider;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProviderController extends Controller
{
  /**
   * Display a listing of the resource.
   */
  public function ProviderManagement()
  {
    return view('content.api-mamagent.provider.index');
  }

  public function index(Request $request)
  {
    $columns = [
      1 => 'id',
      2 => 'provider',
      3 => 'api_key',
      4 => 'status',
    ];

    $search = [];

    $totalData = Provider::count();

    $totalFiltered = $totalData;

    $limit = $request->input('length');
    $start = $request->input('start');
    $order = $columns[$request->input('order.0.column')];
    $dir = $request->input('order.0.dir');

    if (empty($request->input('search.value'))) {
      $providers = Provider::where('jenis', 1)
        ->offset($start)
        ->limit($limit)
        ->orderBy($order, $dir)
        ->get();
    } else {
      $search = $request->input('search.value');

      $providers = Provider::where('jenis', 1) // Filter untuk jenis 1
        ->where(function ($query) use ($search) {
          $query
            ->where('id', 'LIKE', "%{$search}%")
            ->orWhere('provider', 'LIKE', "%{$search}%")
            ->orWhere('api_key', 'LIKE', "%{$search}%");
        })
        ->offset($start)
        ->limit($limit)
        ->orderBy($order, $dir)
        ->get();
      // $providers = Provider::where('id', 'LIKE', "%{$search}%")
      //   ->orWhere('provider', 'LIKE', "%{$search}%")
      //   ->orWhere('api_key', 'LIKE', "%{$search}%")
      //   ->offset($start)
      //   ->limit($limit)
      //   ->orderBy($order, $dir)
      //   ->get();

      $totalFiltered = Provider::where('jenis', 1) // Filter untuk jenis 1
        ->where(function ($query) use ($search) {
          $query
            ->where('id', 'LIKE', "%{$search}%")
            ->orWhere('provider', 'LIKE', "%{$search}%")
            ->orWhere('api_key', 'LIKE', "%{$search}%");
        })
        ->count();
      // $totalFiltered = Provider::where('id', 'LIKE', "%{$search}%")
      //   ->orWhere('provider', 'LIKE', "%{$search}%")
      //   ->orWhere('api_key', 'LIKE', "%{$search}%")
      //   ->count();
    }

    $data = [];

    if (!empty($providers)) {
      // providing a dummy id instead of database ids
      $ids = $start;

      foreach ($providers as $provider) {
        $nestedData['id'] = $provider->id;
        $nestedData['fake_id'] = ++$ids;
        $nestedData['provider'] = $provider->provider;
        $nestedData['api_key'] = $provider->api_key;
        $nestedData['merchant_code'] = $provider->merchant_code;
        $nestedData['status'] = $provider->status;

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
      'provider' => 'required',
      'apikey' => 'required',
      'merchant_code' => 'required',
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

    $provider = $request->input('provider');
    $apiKey = $request->input('apikey');
    $merchantCode = $request->input('merchant_code');
    $providerName = '';

    switch ($provider) {
      case 4:
        $providerName = 'Vip Reseller';
        break;
      case 5:
        $providerName = 'Digiflazz';
        break;
      case 6:
        $providerName = 'Medan Pedia';
        break;
      case 9:
        $providerName = 'Apigames';
        break;
      case 10:
        $providerName = 'MysticID';
        break;
      default:
        $providerName = 'Unknown Provider';
        break;
    }
    $existingProvider = Provider::find($provider);

    if (!$existingProvider) {
      $newProvider = new Provider();
      $newProvider->provider = $providerName;
      $newProvider->api_key = $apiKey;
      $newProvider->merchant_code = $merchantCode;
      $newProvider->id = $provider;
      $newProvider->jenis = 1;
      $newProvider->save();

      if ($newProvider) {
        return response()->json(['title' => 'Well Done!', 'message' => 'Provider Saved!']);
      } else {
        return response()->json(['title' => 'Error', 'message' => 'An error occurred while saving the Provider.']);
      }
    } else {
      $existingProvider->api_key = $apiKey;
      $existingProvider->merchant_code = $merchantCode;
      $existingProvider->save();

      if ($existingProvider) {
        return response()->json(['title' => 'Well Done!', 'message' => 'Provider Saved!']);
      } else {
        return response()->json(['title' => 'Error', 'message' => 'An error occurred while saving the Provider.']);
      }
    }
  }

  /**
   * Display the specified resource.
   */
  public function show(ApiManagement $apiManagement)
  {
    //
  }

  /**
   * Show the form for editing the specified resource.
   */
  public function edit($id)
  {
    $where = ['id' => $id];

    $provider = Provider::where($where)->first();

    return response()->json($provider);
  }

  /**
   * Update the specified resource in storage.
   */
  public function update(Request $request, $id)
  {
    $newStatus = $request->input('newStatus');

    $provider = Provider::find($id);
    if ($provider) {
      $provider->status = $newStatus;
      $provider->save();
      // provider updated
      if ($newStatus == 0) {
        return response()->json([
          'status' => 'success',
          'title' => 'Successfully deactivated!',
          'message' => 'Provider deactivated successfully',
        ]);
      } else {
        return response()->json([
          'status' => 'success',
          'title' => 'Successfully activated!',
          'message' => 'Provider activated successfully',
        ]);
      }
    } else {
      // provider not found
      return response()->json(['status' => 'error', 'title' => 'Failed', 'message' => 'Provider Not Found'], 422);
    }
  }

  /**
   * Remove the specified resource from storage.
   */
  public function destroy($id)
  {
    $provider = Provider::find($id);
    if (!$provider) {
      return response()->json([
        'status' => 'error',
        'message' => 'Data not found.',
        'code' => 404,
        'data' => [],
      ]);
    }
    $provider->delete();
    if ($provider) {
      return response()->json([
        'status' => 'success',
        'message' => 'Data deleted successfully.',
        'code' => 200,
        'data' => [],
      ]);
    } else {
      return response()->json([
        'status' => 'error',
        'message' => 'Internal Server Error',
        'code' => 500,
        'data' => [],
      ]);
    }
  }
}
