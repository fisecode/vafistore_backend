<?php

namespace App\Http\Controllers\pages;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class PostController extends Controller
{
  /**
   * Display a listing of the resource.
   */
  public function index()
  {
    return view('content.pages.posts');
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
    // Validasi data yang diterima dari formulir
    $validator = Validator::make($request->all(), [
      'title' => 'required',
      'content' => 'required',
      // 'meta_desc' => 'required',
      // 'keywords' => 'required',
      'image' => 'image|mimes:jpeg,png,jpg|max:2048',
    ]);

    if ($validator->fails()) {
      $messages = $validator->getMessageBag();

      return redirect()
        ->back()
        ->with('error', $messages->first());
    }

    // $validatedData = $request->validate([
    //   'title' => 'required',
    //   'content' => 'required',
    //   // 'meta_desc' => 'required',
    //   // 'keywords' => 'required',
    //   'image' => 'image|mimes:jpeg,png,jpg,gif|max:2048', // Sesuaikan dengan kebutuhan Anda
    // ]);

    $slug = Str::slug($request->title);
    // Handle file upload (jika ada)
    if ($request->hasFile('image')) {
      $image = $request->file('image');

      // Menghasilkan nama file yang unik dengan slug
      $imageName = $slug . '_' . time() . '.' . $image->getClientOriginalExtension();

      // Simpan gambar dengan nama unik
      $imagePath = $image->storeAs('uploads', $imageName, 'public');
    } else {
      $imagePath = null; // Jika tidak ada file yang diunggah
    }

    // Ambil tanggal sekarang
    $dateNow = now();

    // Ambil nama pengguna (user) dan nama penulis (author) dari sesi
    $user = Auth::user()->user;
    $author = Auth::user()->name;

    // Ambil kata kunci (keywords) dari judul
    $title = $request->title;
    $keywords = $this->generateKeywords($title);

    // Tentukan gambar default jika tidak ada yang diunggah
    $imagePath = $imagePath ?? 'no-photo.jpg';

    // Simpan data artikel ke dalam database
    $post = new Post();
    $post->slug = $slug;
    $post->title = $title;
    $post->meta_desc = ''; // Simpan meta-desc
    $post->keyword = $keywords; // Simpan kata kunci
    $post->image = $imagePath; // Simpan path gambar jika ada
    $post->video = '';
    $post->content = $request->content;
    $post->author = $author; // Simpan nama penulis (author)
    $post->kategori = 'artikel';
    $post->created_date = $dateNow; // Simpan tanggal sekarang
    $post->last_update = $dateNow; // Simpan tanggal sekarang sebagai last_update
    $post->user = $user; // Simpan nama pengguna (user)
    $post->status = 0;
    $post->save();

    if ($post) {
      // Pesan sukses jika data berhasil disimpan
      return redirect()
        ->route('post')
        ->with('success', 'Artikel berhasil disimpan!');
    } else {
      // Pesan gagal jika terjadi kesalahan
      return redirect()
        ->route('post')
        ->with('error', 'Terjadi kesalahan saat menyimpan artikel.');
    }
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

  private function generateKeywords($title)
  {
    // Menghapus karakter khusus dan memecah judul menjadi kata-kata
    $title = preg_replace('/[^a-zA-Z0-9\s]/', '', $title);
    $title = strtolower($title);
    $keywords = explode(' ', $title);

    // Hapus kata-kata yang terlalu pendek (opsional)
    $keywords = array_filter($keywords, function ($keyword) {
      return strlen($keyword) > 2;
    });

    // Menggabungkan kata-kata menjadi string dengan koma sebagai pemisah
    $keywords = implode(',', $keywords);

    return $keywords;
  }
}
