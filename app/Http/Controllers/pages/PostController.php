<?php

namespace App\Http\Controllers\pages;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Traits\ImageStorage;
use Illuminate\Support\Facades\Storage;
use DataTables;
class PostController extends Controller
{
  use ImageStorage;
  /**
   * Display a listing of the resource.
   */
  public function index(Request $request)
  {
    if ($request->ajax()) {
      $post = Post::all();
      return DataTables::of($post)
        ->addIndexColumn()
        ->addColumn('author', function ($post) {
          return $post->user->name;
        })
        ->addColumn('action', function ($post) {
          $edit = route('post.edit', $post->id);
          $delete = route('post.delete', $post->id);
          return '<div class="d-inline-block text-nowrap">
              <a href="' .
            $edit .
            '" class="btn btn-sm btn-icon"><i class="mdi mdi-pencil-outline"></i></a>
              <button class="btn btn-sm btn-icon dropdown-toggle hide-arrow" data-bs-toggle="dropdown"><i class="mdi mdi-dots-vertical me-2"></i></button>
              <div class="dropdown-menu dropdown-menu-end m-0">
                    <a href="javascript:0;" class="dropdown-item">View</a>
                    <a href="#" class="dropdown-item delete-post"
                    data-title="Delete" data-toggle="tooltip" data-original-title="Delete" data-url="' .
            $delete .
            '" data-id="' .
            $post->id .
            '">Delete</a>
            </div>
          </div>';
        })
        ->rawColumns(['action'])
        ->make(true);
    }
    return view('content.pages.posts.list');
  }
  public function add()
  {
    return view('content.pages.posts.form');
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
        ->with('error', $validator->messages()->first());
    }

    // Extract data from the request
    $title = $request->title;
    $content = $request->content;
    $status = $request->status ?? 0; // Default status to 0 if not provided
    $tagArray = json_decode($request->postTags);

    // Extract tags if present
    $tagsString = null;
    if ($tagArray) {
      $tagsString = implode(', ', array_column($tagArray, 'value'));
    }

    // Generate keywords from the title
    $keywords = $this->generateKeywords($title);

    // Create a unique slug
    $slug = Str::slug($title);

    // Handle file upload
    $imagePath = null;
    if ($request->hasFile('image')) {
      $image = $request->file('image');
      $imagePath = $this->uploadImage($image, $title, 'posts');
    } else {
      $imagePath = 'no-photo.jpg'; // Default image path if no file is uploaded
    }

    // Get the current date and time
    $dateNow = now();

    // Create and save the post
    $post = new Post();
    $post->user_id = Auth::user()->id;
    $post->slug = $slug;
    $post->title = $title;
    $post->meta_desc = Str::limit(strip_tags($content), 160);
    $post->keyword = $keywords;
    $post->image = $imagePath;
    $post->content = $content;
    $post->kategori = $request->category;
    $post->tags = $tagsString;
    $post->created_date = $dateNow;
    $post->last_update = $dateNow;
    $post->status = $status;
    $post->save();

    // Redirect with success or error message
    if ($post) {
      $message = $status == 0 ? 'Artikel berhasil published!' : 'Artikel berhasil disimpan sebagai draft.';
      return redirect()
        ->route('post.list')
        ->with('success', $message);
    } else {
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
    $post = Post::find($id); // Mengambil data posting berdasarkan ID

    if (!$post) {
      // Handle jika posting tidak ditemukan
      return redirect()
        ->route('post.list')
        ->with('error', 'Post not found.');
    }

    return view('content.pages.posts.form', compact('post'));
  }

  /**
   * Update the specified resource in storage.
   */
  public function update(Request $request, string $id)
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

    $dateNow = now();

    $post = Post::findOrFail($id);
    $status = $request->status ?? 0;
    $request['last_update'] = $dateNow;

    $imagePath = $post->image;
    $image = null;
    $newTitle = $request->title;
    $oldTitle = $post->title;
    if ($request->hasFile('image')) {
      $image = $request->file('image');
      $path = $this->uploadImage($image, $request->title, 'posts', true, $imagePath);
      $request->replace(['image' => $path]);
    }

    $post->update($request->all());

    if ($image) {
      $post->image = $path;
      $post->save();
    }

    if ($oldTitle !== $newTitle) {
      $newImageName = $this->renameImage($imagePath, $request->title);
      $post->image = $newImageName;
      $post->save();
    }

    if ($post) {
      $message = $status == 0 ? 'Artikel berhasil di update!' : 'Artikel berhasil di unpublish.';
      return redirect()
        ->route('post.list')
        ->with('success', $message);
    } else {
      return redirect()
        ->route('post.list')
        ->with('error', 'Terjadi kesalahan saat menyimpan artikel.');
    }
  }

  /**
   * Remove the specified resource from storage.
   */
  public function destroy(string $id)
  {
    $post = Post::findOrFail($id);
    $image = $post->image;

    if ($image) {
      $this->deleteImage($image, 'posts');
    }

    $post->delete();
    session()->flash('success', 'Post successfully deleted.');
    return redirect()->back();
  }

  public function delete(string $id)
  {
    $post = Post::findOrFail($id);
    return view('content.pages.posts.delete', compact('post'));
  }

  public function getData()
  {
    $post = Post::with('user')->get();
    return response()->json($post);
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

  public function renameImage($imagePath, $newTitle)
  {
    $newName = Str::slug($newTitle) . '-' . time();
    $extension = pathinfo($imagePath, PATHINFO_EXTENSION); // Dapatkan ekstensi gambar dari path yang lama
    $newImageName = $newName . '.' . $extension;

    // Ganti nama file gambar di direktori penyimpanan
    Storage::move("/assets/img/posts/{$imagePath}", "/assets/img/posts/{$newImageName}");

    return $newImageName;
  }
}
