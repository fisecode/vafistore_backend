<?php

namespace App\Http\Controllers\pages;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\PostCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Traits\ImageStorage;
class PostController extends Controller
{
  use ImageStorage;
  /**
   * Display a listing of the resource.
   */
  public function PostManagement()
  {
    return view('content.posts.index');
  }

  public function index(Request $request)
  {
    $columns = [
      1 => 'title',
      2 => 'category_id',
      3 => 'author',
      4 => 'status',
    ];

    $search = [];

    $totalData = Post::count();

    $totalFiltered = $totalData;

    $limit = $request->input('length');
    $start = $request->input('start');
    $order = $columns[$request->input('order.0.column')];
    $dir = $request->input('order.0.dir');

    if (empty($request->input('search.value'))) {
      $posts = Post::offset($start)
        ->limit($limit)
        ->orderBy($order, $dir)
        ->get();
    } else {
      $search = $request->input('search.value');

      $posts = Post::where('id', 'LIKE', "%{$search}%")
        ->orWhere('title', 'LIKE', "%{$search}%")
        ->orWhere('status', 'LIKE', "%{$search}%")
        ->orWhereHas('user', function ($q) use ($search) {
          $q->where('name', 'LIKE', "%{$search}%");
        })
        ->orWhereHas('category', function ($q) use ($search) {
          $q->where('name', 'LIKE', "%{$search}%");
        })
        ->offset($start)
        ->limit($limit)
        ->orderBy($order, $dir)
        ->get();

      $totalFiltered = Post::where('id', 'LIKE', "%{$search}%")
        ->orWhere('title', 'LIKE', "%{$search}%")
        ->orWhere('status', 'LIKE', "%{$search}%")
        ->orWhereHas('user', function ($q) use ($search) {
          $q->where('name', 'LIKE', "%{$search}%");
        })
        ->orWhereHas('category', function ($q) use ($search) {
          $q->where('name', 'LIKE', "%{$search}%");
        })
        ->count();
    }

    $data = [];

    if (!empty($posts)) {
      // providing a dummy id instead of database ids
      $ids = $start;

      foreach ($posts as $post) {
        $nestedData['id'] = $post->id;
        $nestedData['title'] = $post->title;
        $nestedData['category'] = $post->category ? $post->category->name : 'No Category';
        $nestedData['status'] = $post->status;
        $nestedData['image'] = $post->image;
        $nestedData['meta_desc'] = $post->meta_desc;
        $nestedData['author'] = $post->user->name;

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
    $categories = PostCategory::all();
    return view('content.posts.form', compact('categories'));
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

    // Create and save the post
    $post = new Post();
    $post->user_id = Auth::user()->id;
    $post->slug = $slug;
    $post->title = $title;
    $post->meta_desc = Str::limit(strip_tags($content), 160);
    $post->keyword = $keywords;
    $post->image = $imagePath;
    $post->content = $content;
    $post->category_id = $request->category;
    $post->tags = $tagsString;
    $post->status = $status;
    $post->save();

    // Redirect with success or error message
    if ($post) {
      $message = $status == 1 ? 'Article successfully published!' : 'Article successfully saved as draft.';
      return redirect()
        ->route('post-list')
        ->with('success', $message);
    } else {
      return redirect()
        ->route('post-list')
        ->with('error', 'An error occurred while saving the article.');
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
    $categories = PostCategory::all();

    if (!$post) {
      // Handle jika posting tidak ditemukan
      return redirect()
        ->route('post-list')
        ->with('error', 'Post not found.');
    }

    return view('content.posts.form', compact('post', 'categories'));
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

    $post = Post::findOrFail($id);
    $status = $request->status ?? 0;

    // Handle image upload
    $imagePath = $post->image;
    if ($request->hasFile('image')) {
      $image = $request->file('image');
      $imagePath = $this->uploadImage($image, $request->title, 'posts', true, $imagePath);
    }

    // Handle image renaming
    $newTitle = $request->title;
    $oldTitle = $post->title;
    if ($oldTitle !== $newTitle) {
      $newImageName = $this->renameImage($imagePath, $request->title, 'posts');
      $imagePath = $newImageName;
    }

    // Update the post
    $post->update([
      'title' => $request->title,
      'meta_desc' => Str::limit(strip_tags($request->content), 160),
      'keyword' => $request->keywords,
      'image' => $imagePath,
      'content' => $request->content,
      'category_id' => $request->category,
      'tags' => $request->tags,
      'status' => $status,
    ]);

    // Redirect with success or error message
    $message = $status == 1 ? 'Article successfully updated!' : 'Article successfully unpublished.';
    return redirect()
      ->route('post-list')
      ->with('success', $message);
  }

  /**
   * Remove the specified resource from storage.
   */
  public function destroy(string $id)
  {
    // $posts = Post::where('id', $id)->delete();
    $post = Post::findOrFail($id);
    $image = $post->image;

    if ($image) {
      $this->deleteImage($image, 'posts');
    }

    $post->delete();
    // session()->flash('success', 'Post successfully deleted.');
    // return redirect()->back();
  }

  public function delete(string $id)
  {
    $post = Post::findOrFail($id);
    return view('content.posts.delete', compact('post'));
  }

  public function getData()
  {
    $post = Post::with('user')->get();
    return response()->json($post);
  }

  /**
   * Generate keywords from post title.
   */
  private function generateKeywords($title)
  {
    // Remove special characters and split the title into words
    $title = preg_replace('/[^a-zA-Z0-9\s]/', '', $title);
    $title = strtolower($title);
    $keywords = explode(' ', $title);

    // Remove words that are too short (optional)
    $keywords = array_filter($keywords, function ($keyword) {
      return strlen($keyword) > 2;
    });

    // Combine words into a string with comma as separator
    $keywords = implode(',', $keywords);

    return $keywords;
  }
}
