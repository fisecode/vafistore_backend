<?php

namespace App\Http\Controllers\settings;

use App\Http\Controllers\Controller;
use App\Models\ApiManagement;
use App\Models\EntertainmentProduct as Entertainment;
use App\Models\Markup;
use App\Models\Postpaid;
use App\Models\PrepaidProduct as Prepaid;
use App\Models\Product;
use App\Models\VoucherProduct as Voucher;
use App\Models\GameProduct as Game;
use App\Models\ProductCategory;
use App\Models\SocialProduct;
use Illuminate\Http\Request;
use GuzzleHttp\Client;
use Carbon\Carbon;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ServiceController extends Controller
{
  /**
   * Display a listing of the resource.
   */
  public function index()
  {
    $providers = ApiManagement::where('status', 1)
      ->where('jenis', 1)
      ->get();
    return view('content.settings.services.index', compact('providers'));
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
  public function show(string $id)
  {
    //
  }

  /**
   * Show the form for editing the specified resource.
   */
  public function edit(string $id)
  {
    //
  }

  /**
   * Update the specified resource in storage.
   */
  public function update(Request $request, string $id)
  {
    //
  }

  /**
   * Remove the specified resource from storage.
   */
  public function destroy($providerID, $jenis)
  {
    $success = true;
    if ($jenis == 1) {
      $dg = Game::where('provider', $providerID)->delete();
      $dv = Voucher::where('provider', $providerID)->delete();
      if (!$dg && !$dv) {
        $success = false;
      }
    } elseif ($jenis == 2) {
      $delete = Prepaid::where('provider', $providerID)->delete();
      if (!$delete) {
        $success = false;
      }
    } elseif ($jenis == 3) {
      $delete = SocialProduct::where('provider', $providerID)->delete();
      if (!$delete) {
        $success = false;
      }
    } elseif ($jenis == 4) {
      $delete = Entertainment::where('provider', $providerID)->delete();
      if (!$delete) {
        $success = false;
      }
    } elseif ($jenis == 5) {
      $delete = Postpaid::where('provider', $providerID)->delete();
      if (!$delete) {
        $success = false;
      }
    }

    if ($success) {
      return redirect()
        ->route('setting-services')
        ->with('success', 'Semua produk berhasil dihapus');
    } else {
      return redirect()
        ->route('setting-services')
        ->with('error', 'Gagal menghapus beberapa produk');
    }
  }

  public function get($providerID, $jenis)
  {
    $provider = ApiManagement::where('id', $providerID)->first();
    $merchantCodes = $provider->merchant_code;
    $apiKey = $provider->api_key;
    $date = Carbon::now()->toDateString();

    if ($jenis == 1) {
      if ($providerID == 4) {
        $sign = md5($merchantCodes . $apiKey);
        $date = Carbon::now()->toDateString();

        $markUp = Markup::where('id', 1)->first();
        $persen_sell = $markUp->persen_sell;
        $persen_res = $markUp->persen_res;
        $satuan = $markUp->satuan;

        $client = new Client();
        $response = $client->post('https://vip-reseller.co.id/api/game-feature', [
          'form_params' => [
            'key' => $apiKey,
            'sign' => $sign,
            'type' => 'services',
            'filter_type' => 'game',
          ],
          'verify' => false, // Disable SSL verification
        ]);

        $hasil = json_decode($response->getBody(), true);
        if (!$hasil['result']) {
          return redirect()
            ->route('setting-services')
            ->with('error', $hasil['message']);
        } else {
          $success = true;

          set_time_limit(120);
          foreach ($hasil['data'] as $i => $data) {
            $productCategory = new ProductCategory();
            $code = $data['code'];
            $brand = $data['game'];
            $eb = explode(' ', $brand);
            $en = explode(' ', $data['name']);
            $status = $data['status'] == 'available' ? 1 : 0;
            $slug = strtolower(preg_replace('/[^a-zA-Z0-9]/', '-', $brand));
            $image = strtolower(str_replace(' ', '_', $brand)) . '.png';
            $item = str_replace(['’', "'"], '&apos;', $data['name']);
            $product = $eb[0] == 'Voucher' || $en[0] == 'Voucher' ? new Voucher() : new Game();
            $hargaModal = $data['price']['basic'];

            if ($satuan == 0) {
              $hargaJual = ceil(($hargaModal + round(($hargaModal * $persen_sell) / 100)) / 100) * 100;
              $hargaReseller = ceil(($hargaModal + round(($hargaModal * $persen_res) / 100)) / 100) * 100;
            } else {
              $hargaJual = ceil(($hargaModal + $persen_sell) / 100) * 100;
              $hargaReseller = ceil(($hargaModal + $persen_res) / 100) * 100;
            }

            if (
              !$product
                ->where('code', $code)
                ->whereDate('created_at', $date)
                ->exists() &&
              !in_array($brand, [
                'Canva Pro',
                'Apple Gift Card',
                'PicsArt Pro',
                'Tiktok Music',
                'Disney Hotstar',
                'Garena Shell Murah',
                'iQIYI Premium',
                'Netflix Premium',
                'Spotify Premium',
                'Vidio Premier',
                'WeTV Premium',
                'Youtube Premium',
                'Amazon Prime Video',
                'Viu Premium',
                'Resso Premium',
                'Telegram Premium',
                'TikTok Music',
                'Lita',
              ])
            ) {
              if ($eb[0] == 'Voucher' || $en[0] == 'Voucher') {
                $type = 3;
                $category = 'Voucher';
              } else {
                $type = 1;
                $category = 'Top Up';
              }

              $subimage =
                strtolower(str_replace(' ', '_', $brand)) . '_' . strtolower(str_replace(' ', '_', $category)) . '.png';
              $brandLower = strtolower($brand);
              $itemLower = strtolower($item);
              $titleWithoutBrand = str_replace($brandLower, '', $itemLower);
              $titleWithoutBrand = str_replace('-', '', $titleWithoutBrand);
              $titleWithoutBrand = ucwords(trim($titleWithoutBrand));

              $cpc = $productCategory->where('name', $brand)->first();
              if (!$cpc) {
                $productCategory->slug = $slug;
                $productCategory->name = $brand;
                $productCategory->image = $image;
                $productCategory->type = $type;
                $productCategory->subimage = $subimage;
                $productCategory->user_id = Auth::user()->id;
                $productCategory->save();
              }

              $cekProdukDulu = $product
                ->where('provider', 4)
                ->orderBy('id', 'desc')
                ->first();

              if ($cekProdukDulu) {
                $id = $cekProdukDulu->id + 1;
              } else {
                $a = strlen($i);
                if ($a == 1) {
                  $id = '4000' . $i;
                } elseif ($a == 2) {
                  $id = '400' . $i;
                } elseif ($a == 3) {
                  $id = '40' . $i;
                } elseif ($a == 4) {
                  $id = '4' . $i;
                } elseif ($a == 5) {
                  $id = $i;
                }
              }

              $cp = $product->where('code', $code)->first();
              if ($cp) {
                $product->capital_price = $hargaModal;
                $product->selling_price = $hargaJual;
                $product->reseller_price = $hargaReseller;
                $product->status = $status;
                $save = $product->update();
              } else {
                $product->id = $id;
                $product->slug = $slug . '-' . str_replace(' ', '-', strtolower($category));
                $product->code = $code;
                $product->item = $titleWithoutBrand;
                $product->brand = $brand;
                $product->capital_price = $hargaModal;
                $product->selling_price = $hargaJual;
                $product->reseller_price = $hargaReseller;
                $product->currency = '';
                $product->category = $category;
                $product->status = $status;
                $product->provider = $providerID;
                $product->type = $type;
                $save = $product->save();
              }

              if (!$save) {
                $success = false;
              }
            }
          }

          if ($success) {
            return redirect()
              ->route('setting-services')
              ->with('success', 'Semua produk berhasil ditambahkan');
          } else {
            return redirect()
              ->route('setting-services')
              ->with('error', 'Gagal menambahkan beberapa produk');
          }
        }
      } elseif ($providerID == 5) {
        $sign = md5($merchantCodes . $apiKey . '"pricelist"');
        $params = [
          'json' => [
            'cmd' => 'prepaid',
            'username' => $merchantCodes,
            'sign' => $sign,
          ],
          'headers' => [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
          ],
          'verify' => false,
        ];
        $date = Carbon::now()->toDateString();

        // Product::where('jenis', 5)->delete();

        $markUp = Markup::where('id', 1)->first();
        $persen_sell = $markUp->persen_sell;
        $persen_res = $markUp->persen_res;
        $satuan = $markUp->satuan;

        $client = new Client();
        try {
          $response = $client->post('https://api.digiflazz.com/v1/price-list', $params);
          // Lanjutkan dengan pemrosesan respons jika tidak ada kesalahan
          $hasil = json_decode($response->getBody(), true);
          // Lakukan sesuatu dengan data yang diterima
        } catch (ClientException $e) {
          $response = $e->getResponse();
          $statusCode = $response->getStatusCode();

          if ($statusCode == 400) {
            $hasil = json_decode($response->getBody(), true);
            return redirect()
              ->route('setting-services')
              ->with('error', $hasil['data']['message']);
          } else {
            return redirect()
              ->route('setting-services')
              ->with('error', 'HTTP Error: ' . $statusCode);
          }
        } catch (\Exception $e) {
          return redirect()
            ->route('setting-services')
            ->with('error', 'Request Error: ' . $e->getMessage());
        }

        if (isset($hasil['data']['rc'])) {
          return redirect()
            ->route('setting-services')
            ->with('error', $hasil['data']['message']);
        } else {
          $success = true;
          foreach ($hasil['data'] as $i => $data) {
            $productCategory = new ProductCategory();
            $brand = ucwords(strtolower($data['brand']));
            $code = $data['buyer_sku_code'];
            $status = $data['seller_product_status'] == true ? 1 : 0;
            $slug = strtolower(preg_replace('/[^a-zA-Z0-9]/', '-', $brand));
            $image = strtolower(str_replace(' ', '_', $brand)) . '.png';
            $item = str_replace(['’', "'"], '&apos;', $data['product_name']);
            $cat = $data['category'];
            $category = $data['type'];
            $product = $cat == 'Games' ? new Game() : new Voucher();
            $hargaModal = $data['price'];

            if ($satuan == 0) {
              $hargaJual = ceil(($hargaModal + round(($hargaModal * $persen_sell) / 100)) / 100) * 100;
              $hargaReseller = ceil(($hargaModal + round(($hargaModal * $persen_res) / 100)) / 100) * 100;
            } else {
              $hargaJual = ceil(($hargaModal + $persen_sell) / 100) * 100;
              $hargaReseller = ceil(($hargaModal + $persen_res) / 100) * 100;
            }

            if (
              !$product
                ->where('code', $code)
                ->whereDate('created_at', $date)
                ->exists() &&
              in_array($cat, ['Games', 'Voucher'])
            ) {
              $category = $cat == 'Voucher' ? $cat : 'Top Up';
              $type = $cat == 'Games' ? 1 : 3;
              $subimage =
                strtolower(str_replace(' ', '_', $brand)) . '_' . strtolower(str_replace(' ', '_', $category)) . '.png';

              $brandLower = strtolower($brand);
              $itemLower = strtolower($item);
              $titleWithoutBrand = str_replace($brandLower, '', $itemLower);
              $titleWithoutBrand = str_replace('-', '', $titleWithoutBrand);
              $titleWithoutBrand = ucwords(trim($titleWithoutBrand));

              $cpc = $productCategory->where('name', $brand)->first();
              if (!$cpc) {
                $productCategory->slug = $slug;
                $productCategory->name = $brand;
                $productCategory->image = $image;
                $productCategory->type = $type;
                $productCategory->subimage = $subimage;
                $productCategory->user_id = Auth::user()->id;
                $productCategory->save();
              }

              $cekProdukDulu = $product
                ->where('provider', 5)
                ->orderBy('id', 'desc')
                ->first();

              if ($cekProdukDulu) {
                $id = $cekProdukDulu->id + 1;
              } else {
                $a = strlen($i);
                if ($a == 1) {
                  $id = '5000' . $i;
                } elseif ($a == 2) {
                  $id = '500' . $i;
                } elseif ($a == 3) {
                  $id = '50' . $i;
                } elseif ($a == 4) {
                  $id = '5' . $i;
                } elseif ($a == 5) {
                  $id = $i;
                }
              }

              $cp = $product->where('code', $code)->first();
              if ($cp) {
                $product->capital_price = $hargaModal;
                $product->selling_price = $hargaJual;
                $product->reseller_price = $hargaReseller;
                $product->status = $status;
                $save = $product->update();
              } else {
                $product->id = $id;
                $product->slug = $slug . '-' . str_replace(' ', '-', strtolower($category));
                $product->code = $code;
                $product->item = $titleWithoutBrand;
                $product->brand = $brand;
                $product->capital_price = $hargaModal;
                $product->selling_price = $hargaJual;
                $product->reseller_price = $hargaReseller;
                $product->currency = '';
                $product->category = $category;
                $product->status = $status;
                $product->provider = $providerID;
                $product->type = $type;
                $save = $product->save();
              }

              if (!$save) {
                $success = false;
              }
            }
          }

          if ($success) {
            return redirect()
              ->route('setting-services')
              ->with('success', 'Semua produk berhasil ditambahkan');
          } else {
            return redirect()
              ->route('setting-services')
              ->with('error', 'Gagal menambahkan beberapa produk');
          }
        }
      }
    } elseif ($jenis == 2) {
      if ($providerID == 4) {
        $sign = md5($merchantCodes . $apiKey);

        // $delete = Prepaid::where('jenis', 4)->delete();

        $client = new Client();
        $response = $client->post('https://vip-reseller.co.id/api/prepaid', [
          'form_params' => [
            'key' => $apiKey,
            'sign' => $sign,
            'type' => 'services',
          ],
          'verify' => false, // Disable SSL verification
        ]);

        $hasil = json_decode($response->getBody(), true);
        if (!$hasil['result']) {
          return redirect()
            ->route('setting-services')
            ->with('error', $hasil['message']);
        } else {
          $success = true;

          set_time_limit(120);
          foreach ($hasil['data'] as $i => $data) {
            $product = new Prepaid();
            $productCategory = new ProductCategory();
            $code = $data['code'];
            $brand = $data['brand'];
            $category = ucwords(str_replace('-', ' ', $data['type']));
            $status = $data['status'] == 'available' ? 1 : 0;
            $slug = strtolower(preg_replace('/[^a-zA-Z0-9]/', '', $brand));
            $image = strtolower(str_replace(' ', '_', $brand)) . '.png';
            $subimage =
              strtolower(str_replace(' ', '_', $brand)) . '_' . strtolower(str_replace(' ', '_', $category)) . '.png';
            $item = str_replace(['’', "'"], '&apos;', $data['name']);
            $hargaModal = $data['price']['basic'];

            if (
              !$product
                ->where('code', $code)
                ->whereDate('created_at', $date)
                ->exists() &&
              !in_array($category, ['Voucher Game', 'Paket Lainnya', 'Pulsa Internasional', 'Not Filtered'])
            ) {
              $type = $category == 'Saldo Emoney' ? 4 : 3;
              $markUp = Markup::where('id', $type)->first();

              $persen_sell = $markUp->persen_sell;
              $persen_res = $markUp->persen_res;
              $satuan = $markUp->satuan;

              if ($satuan == 0) {
                $hargaJual = ceil(($hargaModal + round(($hargaModal * $persen_sell) / 100)) / 100) * 100;
                $hargaReseller = ceil(($hargaModal + round(($hargaModal * $persen_res) / 100)) / 100) * 100;
              } else {
                $hargaJual = ceil(($hargaModal + $persen_sell) / 100) * 100;
                $hargaReseller = ceil(($hargaModal + $persen_res) / 100) * 100;
              }

              $cpc = $productCategory->where('name', $brand)->first();
              if (!$cpc) {
                $productCategory->slug = $slug;
                $productCategory->name = $brand;
                $productCategory->image = $image;
                $productCategory->type = $type;
                $productCategory->subimage = $subimage;
                $productCategory->user_id = Auth::user()->id;
                $productCategory->save();
              }

              $cekProdukDulu = $product
                ->where('provider', 4)
                ->orderBy('id', 'desc')
                ->first();

              if ($cekProdukDulu) {
                $id = $cekProdukDulu->id + 1;
              } else {
                $a = strlen($i);
                if ($a == 1) {
                  $id = '4000' . $i;
                } elseif ($a == 2) {
                  $id = '400' . $i;
                } elseif ($a == 3) {
                  $id = '40' . $i;
                } elseif ($a == 4) {
                  $id = '4' . $i;
                } elseif ($a == 5) {
                  $id = $i;
                }
              }

              $product->id = $id;
              $product->slug = $slug . '-' . str_replace(' ', '-', strtolower($category));
              $product->code = $code;
              $product->item = $item;
              $product->brand = $brand;
              $product->capital_price = $hargaModal;
              $product->selling_price = $hargaJual;
              $product->reseller_price = $hargaReseller;
              $product->currency = '';
              $product->category = $category;
              $product->status = $status;
              $product->provider = $providerID;
              $product->type = $type;
              $product->save();

              if (!$product->save()) {
                $success = false;
              }
            }
          }

          if ($success) {
            return redirect()
              ->route('setting-services')
              ->with('success', 'Semua produk berhasil ditambahkan');
          } else {
            return redirect()
              ->route('setting-services')
              ->with('error', 'Gagal menambahkan beberapa produk');
          }
        }
      } elseif ($providerID == 5) {
        $sign = md5($merchantCodes . $apiKey . '"pricelist"');
        $params = [
          'json' => [
            'cmd' => 'prepaid',
            'username' => $merchantCodes,
            'sign' => $sign,
          ],
          'headers' => [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
          ],
          'verify' => false,
        ];

        $date = Carbon::now()->toDateString();

        $markUp = Markup::where('id', 1)->first();
        $persen_sell = $markUp->persen_sell;
        $persen_res = $markUp->persen_res;
        $satuan = $markUp->satuan;

        $client = new Client();
        try {
          $response = $client->post('https://api.digiflazz.com/v1/price-list', $params);
          // Lanjutkan dengan pemrosesan respons jika tidak ada kesalahan
          $hasil = json_decode($response->getBody(), true);
          // Lakukan sesuatu dengan data yang diterima
        } catch (ClientException $e) {
          $response = $e->getResponse();
          $statusCode = $response->getStatusCode();

          if ($statusCode == 400) {
            $hasil = json_decode($response->getBody(), true);
            return redirect()
              ->route('setting-services')
              ->with('error', $hasil['data']['message']);
          } else {
            return redirect()
              ->route('setting-services')
              ->with('error', 'HTTP Error: ' . $statusCode);
          }
        } catch (\Exception $e) {
          return redirect()
            ->route('setting-services')
            ->with('error', 'Request Error: ' . $e->getMessage());
        }

        if (isset($hasil['data']['rc'])) {
          return redirect()
            ->route('setting-services')
            ->with('error', $hasil['data']['message']);
        } else {
          $success = true;

          foreach ($hasil['data'] as $i => $data) {
            $product = new Prepaid();
            $productCategory = new ProductCategory();
            $brand = ucwords(strtolower($data['brand']));
            $code = $data['buyer_sku_code'];
            $status = $data['seller_product_status'] == true ? 1 : 0;
            $slug = strtolower(preg_replace('/[^a-zA-Z0-9]/', '', $brand));
            $image = strtolower(str_replace(' ', '_', $brand)) . '.png';
            $item = str_replace(['’', "'"], '&apos;', $data['product_name']);
            $cat = $data['category'];
            $category = $data['type'];
            $hargaModal = $data['price'];

            if (
              !$product
                ->where('code', $code)
                ->whereDate('created_at', $date)
                ->exists() &&
              !in_array($cat, ['Games', 'Voucher'])
            ) {
              $productType = $cat == 'E-Money' ? 4 : 3;
              $subimage =
                strtolower(str_replace(' ', '_', $brand)) . '_' . strtolower(str_replace(' ', '_', $category)) . '.png';

              $brandLower = strtolower($brand);
              $itemLower = strtolower($item);
              $titleWithoutBrand = str_replace($brandLower, '', $itemLower);
              $titleWithoutBrand = str_replace('-', '', $titleWithoutBrand);
              $titleWithoutBrand = ucwords(trim($titleWithoutBrand));

              $markUp = Markup::where('id', $productType)->first();
              $persen_sell = $markUp->persen_sell;
              $persen_res = $markUp->persen_res;
              $satuan = $markUp->satuan;

              if ($satuan == 0) {
                $hargaJual = ceil(($hargaModal + round(($hargaModal * $persen_sell) / 100)) / 100) * 100;
                $hargaReseller = ceil(($hargaModal + round(($hargaModal * $persen_res) / 100)) / 100) * 100;
              } else {
                $hargaJual = ceil(($hargaModal + $persen_sell) / 100) * 100;
                $hargaReseller = ceil(($hargaModal + $persen_res) / 100) * 100;
              }

              $cpc = $productCategory->where('name', $brand)->first();
              if (!$cpc) {
                $productCategory->slug = $slug;
                $productCategory->name = $brand;
                $productCategory->image = $image;
                $productCategory->type = $productType;
                $productCategory->subimage = $subimage;
                $productCategory->user_id = Auth::user()->id;
                $productCategory->save();
              }

              $cekProdukDulu = $product
                ->where('provider', 5)
                ->orderBy('id', 'desc')
                ->first();

              if ($cekProdukDulu) {
                $id = $cekProdukDulu->id + 1;
              } else {
                $a = strlen($i);
                if ($a == 1) {
                  $id = '5000' . $i;
                } elseif ($a == 2) {
                  $id = '500' . $i;
                } elseif ($a == 3) {
                  $id = '50' . $i;
                } elseif ($a == 4) {
                  $id = '5' . $i;
                } elseif ($a == 5) {
                  $id = $i;
                }
              }

              if ($cat == 'E-Money' && $category == 'Umum') {
                $category = 'Saldo E-Money';
              } elseif ($cat == 'Pulsa' && $category == 'Umum') {
                $category = 'Pulsa Regular';
              } elseif ($cat == 'Data') {
                $category = 'Paket Data';
              }

              $cp = $product->where('code', $code)->first();
              if ($cp) {
                $product->capital_price = $hargaModal;
                $product->selling_price = $hargaJual;
                $product->reseller_price = $hargaReseller;
                $product->status = $status;
                $save = $product->update();
              } else {
                $product->id = $id;
                $product->slug = $slug . '-' . str_replace(' ', '-', strtolower($category));
                $product->code = $code;
                $product->item = $titleWithoutBrand;
                $product->brand = $brand;
                $product->capital_price = $hargaModal;
                $product->selling_price = $hargaJual;
                $product->reseller_price = $hargaReseller;
                $product->currency = '';
                $product->category = $category;
                $product->status = $status;
                $product->provider = $providerID;
                $product->type = $productType;
                $save = $product->save();
              }

              if (!$product->save()) {
                $success = false;
              }
            }
          }

          if ($success) {
            return redirect()
              ->route('setting-services')
              ->with('success', 'Semua produk berhasil ditambahkan');
          } else {
            return redirect()
              ->route('setting-services')
              ->with('error', 'Gagal menambahkan beberapa produk');
          }
        }
      }
    } elseif ($jenis == 3) {
      if ($providerID == 4) {
        $sign = md5($merchantCodes . $apiKey);

        $date = Carbon::now()->toDateString();

        $markUp = Markup::where('id', 5)->first();
        $persen_sell = $markUp->persen_sell;
        $persen_res = $markUp->persen_res;
        $satuan = $markUp->satuan;

        $client = new Client();
        $response = $client->post('https://vip-reseller.co.id/api/social-media', [
          'form_params' => [
            'key' => $apiKey,
            'sign' => $sign,
            'type' => 'services',
          ],
          'verify' => false, // Disable SSL verification
        ]);

        $hasil = json_decode($response->getBody(), true);
        if (!$hasil['result']) {
          return redirect()
            ->route('setting-services')
            ->with('error', $hasil['message']);
        } else {
          $success = true;
          foreach ($hasil['data'] as $i => $data) {
            $productCategory = new ProductCategory();
            $product = new SocialProduct();
            $code = $data['id'];
            $explode = explode(' ', $data['category']);
            $category = $explode[0];
            $item = str_replace(['’', "'"], '&apos;', $data['name']);
            $description = str_replace(['’', "'"], '&apos;', $data['note']);
            $minBuy = $data['min'];
            $maxBuy = $data['max'];
            $image = strtolower($category) . '.png';
            $status = $data['status'] == 'available' ? 1 : 0;
            $subimage = strtolower(str_replace(' ', '_', $category)) . '.png';
            $hargaModal = $data['price']['basic'];
            if ($satuan == 0) {
              $hargaJual = ceil(($hargaModal + round(($hargaModal * $persen_sell) / 100)) / 100) * 100;
              $hargaReseller = ceil(($hargaModal + round(($hargaModal * $persen_res) / 100)) / 100) * 100;
            } else {
              $hargaJual = ceil(($hargaModal + $persen_sell) / 100) * 100;
              $hargaReseller = ceil(($hargaModal + $persen_res) / 100) * 100;
            }

            if (
              !$product
                ->where('code', $code)
                ->whereDate('created_at', $date)
                ->exists() &&
              !in_array($category, ['â™•', 'Z', 'Likee', 'Akun', '-'])
            ) {
              $cpc = $productCategory->where('name', $category)->first();
              if (!$cpc) {
                $productCategory->slug = strtolower($category);
                $productCategory->name = $category;
                $productCategory->image = $image;
                $productCategory->type = 5;
                $productCategory->subimage = $subimage;
                $productCategory->user_id = Auth::user()->id;
                $productCategory->save();
              }

              $cekProdukDulu = $product
                ->where('provider', 4)
                ->orderBy('id', 'desc')
                ->first();

              if ($cekProdukDulu) {
                $id = $cekProdukDulu->id + 1;
              } else {
                $a = strlen($i);
                if ($a == 1) {
                  $id = '4000' . $i;
                } elseif ($a == 2) {
                  $id = '400' . $i;
                } elseif ($a == 3) {
                  $id = '40' . $i;
                } elseif ($a == 4) {
                  $id = '4' . $i;
                } elseif ($a == 5) {
                  $id = $i;
                }
              }

              $cp = $product->where('code', $code)->first();
              if ($cp) {
                $product->capital_price = $hargaModal;
                $product->selling_price = $hargaJual;
                $product->reseller_price = $hargaReseller;
                $product->status = $status;
                $save = $product->update();
              } else {
                $product->id = $id;
                $product->slug = $code;
                $product->code = $code;
                $product->item = $item;
                $product->category = $category;
                $product->description = $description;
                $product->min_buy = $minBuy;
                $product->max_buy = $maxBuy;
                $product->capital_price = $hargaModal;
                $product->selling_price = $hargaJual;
                $product->reseller_price = $hargaReseller;
                $product->image = $image;
                $product->status = $status;
                $product->provider = $providerID;
                $product->type = 5;
                $save = $product->save();
              }

              if (!$save) {
                $success = false;
              }
            }
          }

          if ($success) {
            return redirect()
              ->route('setting-services')
              ->with('success', 'Semua produk berhasil ditambahkan');
          } else {
            return redirect()
              ->route('setting-services')
              ->with('error', 'Gagal menambahkan beberapa produk');
          }
        }
      } elseif ($providerID == 6) {
        $date = Carbon::now()->toDateString();

        SocialProduct::where('provider', 6)->delete();

        $markUp = Markup::where('id', 5)->first();
        $persen_sell = $markUp->persen_sell;
        $persen_res = $markUp->persen_res;
        $satuan = $markUp->satuan;

        $client = new Client();
        $response = $client->post('https://api.medanpedia.co.id/services', [
          'form_params' => [
            'api_id' => $merchantCodes,
            'api_key' => $apiKey,
            'type' => 'services',
          ],
          'verify' => false, // Disable SSL verification
        ]);

        $hasil = json_decode($response->getBody(), true);

        if (!$hasil['status']) {
          return redirect()
            ->route('setting-services')
            ->with('error', $hasil['data']);
        } else {
          $success = true;
          foreach ($hasil['data'] as $i => $data) {
            $productCategory = new ProductCategory();
            $product = new SocialProduct();
            $code = $data['id'];
            $explode = explode(' ', $data['category']);
            $category = $explode[0];
            $item = $data['name'];
            $description = str_replace(['’', "'"], '&apos;', $data['description']);
            $minBuy = $data['min'];
            $maxBuy = $data['max'];
            $image = strtolower($category) . '.png';
            $subimage = strtolower(str_replace(' ', '_', $category)) . '.png';
            $hargaModal = $data['price'];
            $status = 1;
            if ($satuan == 0) {
              $hargaJual = ceil(($hargaModal + round(($hargaModal * $persen_sell) / 100)) / 100) * 100;
              $hargaReseller = ceil(($hargaModal + round(($hargaModal * $persen_res) / 100)) / 100) * 100;
            } else {
              $hargaJual = ceil(($hargaModal + $persen_sell) / 100) * 100;
              $hargaReseller = ceil(($hargaModal + $persen_res) / 100) * 100;
            }

            $cpc = $productCategory->where('name', $category)->first();
            if (!$cpc) {
              $productCategory->slug = strtolower($category);
              $productCategory->name = $category;
              $productCategory->image = $image;
              $productCategory->type = 5;
              $productCategory->subimage = $subimage;
              $productCategory->user_id = Auth::user()->id;
              $productCategory->save();
            }

            $a = strlen($i);
            if ($a == 1) {
              $id = '6000' . $i;
            } elseif ($a == 2) {
              $id = '600' . $i;
            } elseif ($a == 3) {
              $id = '60' . $i;
            } elseif ($a == 4) {
              $id = '6' . $i;
            } elseif ($a == 5) {
              $id = $i;
            }

            $product->id = $id;
            $product->slug = $code;
            $product->code = $code;
            $product->item = $item;
            $product->category = $category;
            $product->description = $description;
            $product->min_buy = $minBuy;
            $product->max_buy = $maxBuy;
            $product->capital_price = $hargaModal;
            $product->selling_price = $hargaJual;
            $product->reseller_price = $hargaReseller;
            $product->image = $image;
            $product->status = $status;
            $product->provider = $providerID;
            $product->type = 5;
            $save = $product->save();

            if (!$save) {
              $success = false;
            }
          }

          if ($success) {
            return redirect()
              ->route('setting-services')
              ->with('success', 'Semua produk berhasil ditambahkan');
          } else {
            return redirect()
              ->route('setting-services')
              ->with('error', 'Gagal menambahkan beberapa produk');
          }
        }
      }
    } elseif ($jenis == 4) {
      if ($providerID == 4) {
        $sign = md5($merchantCodes . $apiKey);
        $date = Carbon::now()->toDateString();

        $markUp = Markup::where('id', 1)->first();
        $persen_sell = $markUp->persen_sell;
        $persen_res = $markUp->persen_res;
        $satuan = $markUp->satuan;

        $client = new Client();
        $response = $client->post('https://vip-reseller.co.id/api/game-feature', [
          'form_params' => [
            'key' => $apiKey,
            'sign' => $sign,
            'type' => 'services',
            'filter_type' => 'game',
          ],
          'verify' => false, // Disable SSL verification
        ]);

        $hasil = json_decode($response->getBody(), true);
        if (!$hasil['result']) {
          return redirect()
            ->route('setting-services')
            ->with('error', $hasil['message']);
        } else {
          $success = true;

          foreach ($hasil['data'] as $i => $data) {
            $product = new Entertainment();
            $productCategory = new ProductCategory();
            $code = $data['code'];
            $brand = $data['game'];
            $status = $data['status'] == 'available' ? 1 : 0;
            $slug = strtolower(preg_replace('/[^a-zA-Z0-9]/', '-', $brand));
            $image = strtolower(str_replace(' ', '_', $brand)) . '.png';
            $item = str_replace(['’', "'"], '&apos;', $data['name']);
            $category = 'entertainment';
            $hargaModal = $data['price']['basic'];

            if ($satuan == 0) {
              $hargaJual = ceil(($hargaModal + round(($hargaModal * $persen_sell) / 100)) / 100) * 100;
              $hargaReseller = ceil(($hargaModal + round(($hargaModal * $persen_res) / 100)) / 100) * 100;
            } else {
              $hargaJual = ceil(($hargaModal + $persen_sell) / 100) * 100;
              $hargaReseller = ceil(($hargaModal + $persen_res) / 100) * 100;
            }

            if (
              !$product
                ->where('code', $code)
                ->whereDate('created_at', $date)
                ->exists() &&
              in_array($brand, [
                'Canva Pro',
                'Apple Gift Card',
                'PicsArt Pro',
                'Tiktok Music',
                'Disney Hotstar',
                'iQIYI Premium',
                'Netflix Premium',
                'Spotify Premium',
                'Vidio Premier',
                'WeTV Premium',
                'Youtube Premium',
                'Amazon Prime Video',
                'Viu Premium',
                'Resso Premium',
                'Telegram Premium',
                'TikTok Music',
                'Lita',
              ])
            ) {
              $subimage =
                strtolower(str_replace(' ', '_', $brand)) . '_' . strtolower(str_replace(' ', '_', $category)) . '.png';
              $brandLower = strtolower($brand);
              $itemLower = strtolower($item);
              $titleWithoutBrand = str_replace($brandLower, '', $itemLower);
              $titleWithoutBrand = str_replace('-', '', $titleWithoutBrand);
              $titleWithoutBrand = ucwords(trim($titleWithoutBrand));

              $cpc = $productCategory->where('name', $brand)->first();
              if (!$cpc) {
                $productCategory->slug = $slug;
                $productCategory->name = $brand;
                $productCategory->image = $image;
                $productCategory->type = 6;
                $productCategory->subimage = $subimage;
                $productCategory->user_id = Auth::user()->id;
                $productCategory->save();
              }

              $cekProdukDulu = $product
                ->where('provider', 6)
                ->orderBy('id', 'desc')
                ->first();

              if ($cekProdukDulu) {
                $id = $cekProdukDulu->id + 1;
              } else {
                $a = strlen($i);
                if ($a == 1) {
                  $id = '4000' . $i;
                } elseif ($a == 2) {
                  $id = '400' . $i;
                } elseif ($a == 3) {
                  $id = '40' . $i;
                } elseif ($a == 4) {
                  $id = '4' . $i;
                } elseif ($a == 5) {
                  $id = $i;
                }
              }
              $cp = $product->where('code', $code)->first();

              if ($cp) {
                $product->capital_price = $hargaModal;
                $product->selling_price = $hargaJual;
                $product->reseller_price = $hargaReseller;
                $product->status = $status;
                $save = $product->update();
              } else {
                $product->id = $id;
                $product->slug = $slug . '-' . str_replace(' ', '-', strtolower($category));
                $product->code = $code;
                $product->item = $titleWithoutBrand;
                $product->brand = $brand;
                $product->capital_price = $hargaModal;
                $product->selling_price = $hargaJual;
                $product->reseller_price = $hargaReseller;
                $product->currency = '';
                $product->category = $category;
                $product->status = $status;
                $product->provider = $providerID;
                $product->type = 6;
                $save = $product->save();
              }

              if (!$save) {
                $success = false;
              }
            }
          }

          if ($success) {
            return redirect()
              ->route('setting-services')
              ->with('success', 'Semua produk berhasil ditambahkan');
          } else {
            return redirect()
              ->route('setting-services')
              ->with('error', 'Gagal menambahkan beberapa produk');
          }
        }
      }
    } elseif ($jenis == 5) {
      if ($providerID == 5) {
        $sign = md5($merchantCodes . $apiKey . '"pricelist"');
        $params = [
          'json' => [
            'cmd' => 'pasca',
            'username' => $merchantCodes,
            'sign' => $sign,
          ],
          'headers' => [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
          ],
          'verify' => false,
        ];

        $date = Carbon::now()->toDateString();

        Postpaid::where('jenis', 5)->delete();

        $markUp = Markup::where('id', 7)->first();
        $persen_sell = $markUp->persen_sell;
        $persen_res = $markUp->persen_res;
        $satuan = $markUp->satuan;

        $client = new Client();
        $response = $client->post('https://api.digiflazz.com/v1/price-list', $params);

        $hasil = json_decode($response->getBody(), true);

        $success = true;

        foreach ($hasil['data'] as $i => $data) {
          $produk = new Postpaid();
          $brand = ucwords(strtolower($data['brand']));
          $code = $data['buyer_sku_code'];
          $name = $data['product_name'];
          $slug = strtolower(preg_replace('/[^a-zA-Z0-9]/', '', $brand));
          $image = strtolower(str_replace(' ', '_', $brand)) . '.png';
          $title = str_replace(['’', "'"], '&apos;', $name);
          $kategori = $data['category'];
          $hargaModal = $data['admin'];
          $markUp = Markup::where('id', 7)->first();
          $persen_sell = $markUp->persen_sell;
          $persen_res = $markUp->persen_res;
          $satuan = $markUp->satuan;

          if ($satuan == 0) {
            $hargaJual = ceil(($hargaModal + round(($hargaModal * $persen_sell) / 100)) / 100) * 100;
            $hargaReseller = ceil(($hargaModal + round(($hargaModal * $persen_res) / 100)) / 100) * 100;
          } else {
            $hargaJual = ceil(($hargaModal + $persen_sell) / 100) * 100;
            $hargaReseller = ceil(($hargaModal + $persen_res) / 100) * 100;
          }

          if (
            !$produk
              ->where('code', $code)
              ->whereDate('created_at', $date)
              ->exists() &&
            !in_array($brand, ['PDAM', 'PBB'])
          ) {
            $cekProdukDulu = Postpaid::where('jenis', 4)
              ->orderBy('id', 'desc')
              ->first();

            if ($cekProdukDulu) {
              $id = $cekProdukDulu->id + 1;
            } else {
              $a = strlen($i);
              if ($a == 1) {
                $id = '5000' . $i;
              } elseif ($a == 2) {
                $id = '500' . $i;
              } elseif ($a == 3) {
                $id = '50' . $i;
              } elseif ($a == 4) {
                $id = '5' . $i;
              } elseif ($a == 5) {
                $id = $i;
              }
            }

            $produk->id = $id;
            $produk->slug = $slug;
            $produk->code = $code;
            $produk->title = $title;
            $produk->kategori = $brand;
            $produk->harga_modal = $hargaModal;
            $produk->harga_jual = $hargaJual;
            $produk->harga_reseller = $hargaReseller;
            $produk->image = $image;
            $produk->currency = '';
            $produk->status = 1;
            $produk->jenis = 5;
            $produk->product_type = 6;
            $produk->save();

            if (!$produk->save()) {
              $success = false;
            }
          }
        }

        if ($success) {
          return redirect()
            ->route('setting-services')
            ->with('success', 'Semua produk berhasil ditambahkan');
        } else {
          return redirect()
            ->route('setting-services')
            ->with('error', 'Gagal menambahkan beberapa produk');
        }
      }
    }
  }
}
