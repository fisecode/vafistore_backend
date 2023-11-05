<?php

namespace App\Http\Controllers\pages;

use App\Http\Controllers\Controller;
use App\Models\ApiManagement;
use App\Models\Markup;
use App\Models\Prepaid;
use App\Models\Product;
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
    return view('content.service-page.index', compact('providers'));
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
  public function destroy(string $id)
  {
    //
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
        $persen_flash = $markUp->persen_flash;
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
            $hargaFlash = $hargaModal + round(($hargaModal * $persen_flash) / 100);
          } else {
            $hargaJual = ceil(($hargaModal + $persen_sell) / 100) * 100;
            $hargaReseller = ceil(($hargaModal + $persen_res) / 100) * 100;
            $hargaFlash = $hargaModal + $persen_flash;
          }

          $desiredEndDigits = 99;
          // Mengecek jika nilai terakhir tidak 99
          if ($hargaFlash % 100 != $desiredEndDigits) {
            $hargaFlash = ceil($hargaFlash / 100) * 100 + $desiredEndDigits;
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
              $id = str_pad($i, 4, '0', STR_PAD_LEFT);
            }

            $produk->id = $id;
            $produk->slug = $slug;
            $produk->code = $code;
            $produk->title = $title;
            $produk->kategori = $game;
            $produk->harga_modal = $hargaModal;
            $produk->harga_jual = $hargaJual;
            $produk->harga_reseller = $hargaReseller;
            $produk->harga_flash = $hargaFlash;
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
            $persen_flash = $markUp->persen_flash;
            $satuan = $markUp->satuan;

            if ($satuan == 0) {
              $hargaJual = ceil(($hargaModal + round(($hargaModal * $persen_sell) / 100)) / 100) * 100;
              $hargaReseller = ceil(($hargaModal + round(($hargaModal * $persen_res) / 100)) / 100) * 100;
              $hargaFlash = $hargaModal + round(($hargaModal * $persen_flash) / 100);
            } else {
              $hargaJual = ceil(($hargaModal + $persen_sell) / 100) * 100;
              $hargaReseller = ceil(($hargaModal + $persen_res) / 100) * 100;
              $hargaFlash = $hargaModal + $persen_flash;
            }

            $desiredEndDigits = 99;
            if ($hargaFlash % 100 != $desiredEndDigits) {
              $hargaFlash = ceil($hargaFlash / 100) * 100 + $desiredEndDigits;
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
            $produk->harga_flash = $hargaFlash;
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
      }
    }
  }
}
