<?php

namespace App\Http\Controllers\settings;

use App\Http\Controllers\Controller;
use App\Models\ApiManagement;
use App\Models\Markup;
use App\Models\Postpaid;
use App\Models\Prepaid;
use App\Models\Product;
use App\Models\SocialProduct;
use Illuminate\Http\Request;
use GuzzleHttp\Client;
use Carbon\Carbon;

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
      $delete = Product::where('jenis', $providerID)->where('product_type', $jenis)->delete();
      if (!$delete) {
        $success = false;
      }
    } elseif ($jenis == 2) {
      $delete = Prepaid::where('jenis', $providerID)->delete();
      if (!$delete) {
        $success = false;
      }
    } elseif ($jenis == 3) {
      $delete = SocialProduct::where('jenis', $providerID)->delete();
      if (!$delete) {
        $success = false;
      }
    } elseif ($jenis == 4) {
      $delete = Product::where('jenis', $providerID)->where('product_type', 2)->delete();
      if (!$delete) {
        $success = false;
      }
    } elseif ($jenis == 5) {
      $delete = Postpaid::where('jenis', $providerID)->delete();
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

        Product::where('jenis', 4)
          ->where('product_type', 1)
          ->delete();

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
        ]);

        $hasil = json_decode($response->getBody(), true);
        $success = true;

        foreach ($hasil['data'] as $i => $data) {
          $produk = new Product();
          $code = $data['code'];
          $game = $data['game'];
          $slug = strtolower(preg_replace('/[^a-zA-Z0-9]/', '', $game));
          $image = strtolower(str_replace(' ', '_', $game)) . '.png';
          $title = str_replace(['’', "'"], '&apos;', $data['name']);
          $hargaModal = $data['price']['basic'];

          if ($satuan == 0) {
            $hargaJual = ceil(($hargaModal + round(($hargaModal * $persen_sell) / 100)) / 100) * 100;
            $hargaReseller = ceil(($hargaModal + round(($hargaModal * $persen_res) / 100)) / 100) * 100;
          } else {
            $hargaJual = ceil(($hargaModal + $persen_sell) / 100) * 100;
            $hargaReseller = ceil(($hargaModal + $persen_res) / 100) * 100;
          }

          $tipeData = $data['status'];
          if (
            $tipeData == 'available' &&
            !$produk
              ->where('code', $code)
              ->whereDate('created_at', $date)
              ->exists() &&
            !in_array($game, [
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
            ])
          ) {
            $cekProdukDulu = Product::where('jenis', 4)
              ->orderBy('id', 'desc')
              ->first();

            if ($cekProdukDulu) {
              $id = $cekProdukDulu->id + 1;
            } else {
              $a = strlen($i);
              if ($a == 1) {
                $id = '4000' . $i;
              } else if ($a == 2) {
                $id = '400' . $i;
              } else if ($a == 3) {
                $id = '40' . $i;
              } else if ($a == 4) {
                $id = '4' . $i;
              } else if ($a == 5) {
                $id = $i;
              }
            }

            $produk->id = $id;
            $produk->slug = $slug;
            $produk->code = $code;
            $produk->title = $title;
            $produk->kategori = $game;
            $produk->harga_modal = $hargaModal;
            $produk->harga_jual = $hargaJual;
            $produk->harga_reseller = $hargaReseller;
            $produk->image = $image;
            $produk->currency = '';
            $produk->type = 'Umum';
            $produk->status = 1;
            $produk->jenis = 4;
            $produk->product_type = 1;
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

        Product::where('jenis', 5)
          ->delete();

        $markUp = Markup::where('id', 1)->first();
        $persen_sell = $markUp->persen_sell;
        $persen_res = $markUp->persen_res;
        $satuan = $markUp->satuan;

        $client = new Client();
        $response = $client->post('https://api.digiflazz.com/v1/price-list', $params);

        $hasil = json_decode($response->getBody(), true);
        $success = true;

        foreach ($hasil['data'] as $i => $data) {
          $a = strlen($i);
          if ($a == 1) {
            $id = '5000' . $i;
          } else if ($a == 2) {
            $id = '500' . $i;
          } else if ($a == 3) {
            $id = '50' . $i;
          } else if ($a == 4) {
            $id = '5' . $i;
          } else if ($a == 5) {
            $id = $i;
          }
          $produk = new Product();
          $brand  = ucwords(strtolower($data['brand']));
          $code = $data['buyer_sku_code'];
          $name = $data['product_name'];
          $slug = strtolower(preg_replace('/[^a-zA-Z0-9]/', '', $brand));
          $image = strtolower(str_replace(' ', '_', $brand)) . '.png';
          $title = str_replace(['’', "'"], '&apos;', $name);
          $kategori = $data['category'];
          $type = $data['type'];
          $hargaModal = $data['price'];

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
            in_array($kategori, ['Games', 'Voucher'])
          ) {
            $type = $kategori == 'Voucher' ? $kategori : $type;

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
            $produk->type = $type;
            $produk->status = 1;
            $produk->jenis = 5;
            $produk->product_type = 1;
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
    } elseif ($jenis == 2) {
      if ($providerID == 4) {
        $sign = md5($merchantCodes . $apiKey);

        $delete = Prepaid::where('jenis', 4)->delete();

        $client = new Client();
        $response = $client->post('https://vip-reseller.co.id/api/prepaid', [
          'form_params' => [
            'key' => $apiKey,
            'sign' => $sign,
            'type' => 'services',
          ],
        ]);

        $hasil = json_decode($response->getBody(), true);
        $success = true;

        foreach ($hasil['data'] as $i => $data) {
          $a = strlen($i);
          $id = $a < 5 ? sprintf('%s%s', str_pad('4', 5 - $a, '0', STR_PAD_RIGHT), $i) : $i;
          $produk = new Prepaid();
          $code = $data['code'];
          $brand = $data['brand'];
          $kategori = $data['type'];
          $image = strtolower(str_replace(' ', '_', $brand)) . '.png';
          $title = str_replace(['’', "'"], '&apos;', $data['name']);
          $slug = strtolower(preg_replace('/[^a-zA-Z0-9]/', '', $title));
          $hargaModal = $data['price']['basic'];

          $tipeData = $data['status'];
          if (
            $tipeData == 'available' &&
            !$produk
              ->where('code', $code)
              ->whereDate('created_at', $date)
              ->exists() &&
            !in_array($kategori, ['voucher-game', 'paket-lainnya', 'pulsa-internasional'])
          ) {
            $productType = $kategori == 'saldo-emoney' ? 4 : 3;
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

            $produk->id = $id;
            $produk->slug = $slug;
            $produk->code = $code;
            $produk->title = $title;
            $produk->kategori = $kategori;
            $produk->brand = $brand;
            $produk->harga_modal = $hargaModal;
            $produk->harga_jual = $hargaJual;
            $produk->harga_reseller = $hargaReseller;
            $produk->image = $image;
            $produk->status = 1;
            $produk->jenis = 4;
            $produk->product_type = $productType;
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

        Prepaid::where('jenis', 5)->delete();

        $markUp = Markup::where('id', 1)->first();
        $persen_sell = $markUp->persen_sell;
        $persen_res = $markUp->persen_res;
        $satuan = $markUp->satuan;

        $client = new Client();
        $response = $client->post('https://api.digiflazz.com/v1/price-list', $params);

        $hasil = json_decode($response->getBody(), true);

        $success = true;

        foreach ($hasil['data'] as $i => $data) {
          $a = strlen($i);
          if ($a == 1) {
            $id = '5000' . $i;
          } else if ($a == 2) {
            $id = '500' . $i;
          } else if ($a == 3) {
            $id = '50' . $i;
          } else if ($a == 4) {
            $id = '5' . $i;
          } else if ($a == 5) {
            $id = $i;
          }
          $produk = new Prepaid();
          $brand  = ucwords(strtolower($data['brand']));
          $code = $data['buyer_sku_code'];
          $name = $data['product_name'];
          $slug = strtolower(preg_replace('/[^a-zA-Z0-9]/', '', $brand));
          $image = strtolower(str_replace(' ', '_', $brand)) . '.png';
          $title = str_replace(['’', "'"], '&apos;', $name);
          $kategori = $data['category'];
          $type = strtolower(str_replace(' ', '-', $data['type']));
          $hargaModal = $data['price'];

          if (
            !$produk
              ->where('code', $code)
              ->whereDate('created_at', $date)
              ->exists() &&
            !in_array($kategori, ['Games', 'Voucher'])
          ) {
            $productType = $kategori == 'E-Money' ? 4 : 3;
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

            if ($kategori == 'E-Money' && $type == 'umum') {
              $type = 'saldo-emoney';
            } elseif ($kategori == 'Pulsa' && $type == 'umum') {
              $type = 'pulsa-regular';
            }

            $produk->id = $id;
            $produk->slug = $slug;
            $produk->code = $code;
            $produk->title = $title;
            $produk->kategori = $type;
            $produk->brand = $brand;
            $produk->harga_modal = $hargaModal;
            $produk->harga_jual = $hargaJual;
            $produk->harga_reseller = $hargaReseller;
            $produk->image = $image;
            $produk->status = 1;
            $produk->jenis = 5;
            $produk->product_type = $productType;
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
    } elseif ($jenis == 3) {
      if ($providerID == 4) {
        $sign = md5($merchantCodes . $apiKey);

        $date = Carbon::now()->toDateString();

        SocialProduct::where('jenis', 4)
          ->delete();

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
        ]);

        $hasil = json_decode($response->getBody(), true);

        $success = true;
        foreach ($hasil['data'] as $i => $data) {
          $a = strlen($i);
          if ($a == 1) {
            $id = '4000' . $i;
          } else if ($a == 2) {
            $id = '400' . $i;
          } else if ($a == 3) {
            $id = '40' . $i;
          } else if ($a == 4) {
            $id = '4' . $i;
          } else if ($a == 5) {
            $id = $i;
          }
          $produk = new SocialProduct();
          $code = $data['id'];
          $explode = explode(' ', $data['category']);
          $category = $explode[0];
          $title = str_replace(['’', "'"], '&apos;', $data['name']);
          $deskripsi = str_replace(array("’", "'"), "&apos;", $data['note']);
          $minBuy = $data['min'];
          $maxBuy = $data['max'];
          $image = strtolower($category) . '.png';
          $tipeData = $data['status'];
          $hargaModal = $data['price']['basic'];
          if ($satuan == 0) {
            $hargaJual = ceil(($hargaModal + round(($hargaModal * $persen_sell) / 100)) / 100) * 100;
            $hargaReseller = ceil(($hargaModal + round(($hargaModal * $persen_res) / 100)) / 100) * 100;
          } else {
            $hargaJual = ceil(($hargaModal + $persen_sell) / 100) * 100;
            $hargaReseller = ceil(($hargaModal + $persen_res) / 100) * 100;
          }

          if (
            $tipeData == 'available' &&
            !$produk
              ->where('code', $code)
              ->whereDate('created_at', $date)
              ->exists()
          ) {

            $produk->id = $id;
            $produk->slug = $code;
            $produk->code = $code;
            $produk->title = $title;
            $produk->kategori = $category;
            $produk->deskripsi = $deskripsi;
            $produk->min_buy = $minBuy;
            $produk->max_buy = $maxBuy;
            $produk->harga_modal = $hargaModal;
            $produk->harga_jual = $hargaJual;
            $produk->harga_reseller = $hargaReseller;
            $produk->image = $image;
            $produk->status = 1;
            $produk->jenis = 4;
            $produk->product_type = 5;
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
      } elseif ($providerID == 6) {
        $date = Carbon::now()->toDateString();

        SocialProduct::where('jenis', 6)
          ->delete();

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
        ]);

        $hasil = json_decode($response->getBody(), true);

        $success = true;
        foreach ($hasil['data'] as $i => $data) {
          $a = strlen($i);
          if ($a == 1) {
            $id = '6000' . $i;
          } else if ($a == 2) {
            $id = '600' . $i;
          } else if ($a == 3) {
            $id = '60' . $i;
          } else if ($a == 4) {
            $id = '6' . $i;
          } else if ($a == 5) {
            $id = $i;
          }
          $produk = new SocialProduct();
          $code = $data['id'];
          $explode = explode(' ', $data['category']);
          $category = $explode[0];
          $title = $data['name'];
          $deskripsi = str_replace(array("’", "'"), "&apos;", $data['description']);
          $minBuy = $data['min'];
          $maxBuy = $data['max'];
          $image = strtolower($category) . '.png';
          $hargaModal = $data['price'];
          if ($satuan == 0) {
            $hargaJual = ceil(($hargaModal + round(($hargaModal * $persen_sell) / 100)) / 100) * 100;
            $hargaReseller = ceil(($hargaModal + round(($hargaModal * $persen_res) / 100)) / 100) * 100;
          } else {
            $hargaJual = ceil(($hargaModal + $persen_sell) / 100) * 100;
            $hargaReseller = ceil(($hargaModal + $persen_res) / 100) * 100;
          }

          $produk->id = $id;
          $produk->slug = $code;
          $produk->code = $code;
          $produk->title = $title;
          $produk->kategori = $category;
          $produk->deskripsi = $deskripsi;
          $produk->min_buy = $minBuy;
          $produk->max_buy = $maxBuy;
          $produk->harga_modal = $hargaModal;
          $produk->harga_jual = $hargaJual;
          $produk->harga_reseller = $hargaReseller;
          $produk->image = $image;
          $produk->status = 1;
          $produk->jenis = 6;
          $produk->product_type = 5;
          $produk->save();

          if (!$produk->save()) {
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
    } elseif ($jenis == 4) {
      if ($providerID == 4) {
        $sign = md5($merchantCodes . $apiKey);
        $date = Carbon::now()->toDateString();

        Product::where('jenis', 4)
          ->where('product_type', 2)
          ->delete();

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
        ]);

        $hasil = json_decode($response->getBody(), true);
        $success = true;

        foreach ($hasil['data'] as $i => $data) {
          $produk = new Product();
          $code = $data['code'];
          $game = $data['game'];
          $slug = strtolower(preg_replace('/[^a-zA-Z0-9]/', '', $game));
          $image = strtolower(str_replace(' ', '_', $game)) . '.png';
          $title = str_replace(['’', "'"], '&apos;', $data['name']);
          $hargaModal = $data['price']['basic'];

          if ($satuan == 0) {
            $hargaJual = ceil(($hargaModal + round(($hargaModal * $persen_sell) / 100)) / 100) * 100;
            $hargaReseller = ceil(($hargaModal + round(($hargaModal * $persen_res) / 100)) / 100) * 100;
          } else {
            $hargaJual = ceil(($hargaModal + $persen_sell) / 100) * 100;
            $hargaReseller = ceil(($hargaModal + $persen_res) / 100) * 100;
          }

          $tipeData = $data['status'];
          if (
            $tipeData == 'available' &&
            !$produk
              ->where('code', $code)
              ->whereDate('created_at', $date)
              ->exists() &&
            in_array($game, [
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
            ])
          ) {
            $cekProdukDulu = Product::where('jenis', 4)
              ->orderBy('id', 'desc')
              ->first();

            if ($cekProdukDulu) {
              $id = $cekProdukDulu->id + 1;
            } else {
              $a = strlen($i);
              if ($a == 1) {
                $id = '4000' . $i;
              } else if ($a == 2) {
                $id = '400' . $i;
              } else if ($a == 3) {
                $id = '40' . $i;
              } else if ($a == 4) {
                $id = '4' . $i;
              } else if ($a == 5) {
                $id = $i;
              }
            }

            $produk->id = $id;
            $produk->slug = $slug;
            $produk->code = $code;
            $produk->title = $title;
            $produk->kategori = $game;
            $produk->harga_modal = $hargaModal;
            $produk->harga_jual = $hargaJual;
            $produk->harga_reseller = $hargaReseller;
            $produk->image = $image;
            $produk->currency = '';
            $produk->type = 'Umum';
            $produk->status = 1;
            $produk->jenis = 4;
            $produk->product_type = 2;
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
          $brand  = ucwords(strtolower($data['brand']));
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
              } else if ($a == 2) {
                $id = '500' . $i;
              } else if ($a == 3) {
                $id = '50' . $i;
              } else if ($a == 4) {
                $id = '5' . $i;
              } else if ($a == 5) {
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
