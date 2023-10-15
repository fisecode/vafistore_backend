<?php

namespace App\Http\Controllers\pages;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Toastr;
class PostController extends Controller
{
  /**
   * Display a listing of the resource.
   */
  public function index()
  {
    return view('content.pages.posts.list');
  }
  public function indexAdd()
  {
    return view('content.pages.posts.add');
  }
  public function indexCategory()
  {
    return view('content.pages.posts.category');
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

    $status = $request->status;

    // Ambil kata kunci (keywords) dari judul
    $title = $request->title;
    $keywords = $this->generateKeywords($title);
    $content = $request->content;
    $metaDesc = Str::limit(strip_tags($content), 160);

    // Validasi data yang diterima dari formulir
    $tagArray = json_decode($request->postTags);

    if ($tagArray) {
      // Extract the 'value' property from each element and join them with a comma
      $tagsString = implode(', ', array_column($tagArray, 'value'));
    } else {
      $tagsString = null; // Handle the case where the JSON input is not present or invalid
    }

    // Ambil nama pengguna (user) dan nama penulis (author) dari sesi
    $userId = Auth::user()->id;

    $slug = Str::slug($title);
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

    // Tentukan gambar default jika tidak ada yang diunggah
    $imagePath = $imagePath ?? 'no-photo.jpg';

    // Simpan data artikel ke dalam database
    $post = new Post();
    $post->user_id = $userId;
    $post->slug = $slug;
    $post->title = $title;
    $post->meta_desc = $metaDesc; // Simpan meta-desc
    $post->keyword = $keywords; // Simpan kata kunci
    $post->image = $imagePath; // Simpan path gambar jika ada
    $post->content = $content;
    $post->kategori = $request->category;
    $post->tags = $tagsString;
    $post->created_date = $dateNow; // Simpan tanggal sekarang
    $post->last_update = $dateNow; // Simpan tanggal sekarang sebagai last_update
    $post->status = $status;
    $post->save();

    if ($post) {
      // Pesan sukses jika data berhasil disimpan
      if ($status == 0) {
        return redirect()
          ->route('post.list')
          ->with('success', 'Artikel berhasil published!');
      } else {
        return redirect()
          ->route('post.list')
          ->with('success', 'Artikel berhasil disimpan sebagai draft.');
      }
    } else {
      // Pesan gagal jika terjadi kesalahan
      return redirect()
        ->route('post.list')
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
