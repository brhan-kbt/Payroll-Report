<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Category;
use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PostController extends Controller
{
    protected $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }
    /**
     * Display a listing of the resource.
     */
   public function index(Request $request)
{
    $query = Post::with(['category', 'user']);

    // Apply search if query string exists
    if ($search = $request->input('q')) {
        $query->where(function ($q) use ($search) {
            $q->where('title', 'like', "%{$search}%")
              ->orWhere('subtitle', 'like', "%{$search}%")
              ->orWhere('body', 'like', "%{$search}%")
              ->orWhereHas('category', function ($cat) use ($search) {
                  $cat->where('name', 'like', "%{$search}%");
              })
              ->orWhereHas('user', function ($user) use ($search) {
                  $user->where('name', 'like', "%{$search}%");
              });
        });
    }

    $posts = $query->orderBy('created_at', 'desc')->paginate(10);

    // Preserve search query when paginating
    $posts->appends(['q' => $request->input('q')]);

    return view('posts.index', compact('posts'));
}


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::orderBy('name')->get();
        return view('posts.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePostRequest $request)
    {
        $data = $request->validated();

        // Handle image upload
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '_' . Str::slug($data['title']) . '.' . $image->getClientOriginalExtension();
            $imagePath = $image->storeAs('posts', $imageName, 'public');
            $data['image'] = $imagePath;
        }

        // Set user_id to current user
        $data['user_id'] = auth()->id();

        $post = Post::create($data);

        // Dispatch notification job for new post
        // $this->notificationService->sendNewPostNotification($post);

        return redirect()->route('posts.index')
            ->with('success', 'Post created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Post $post)
    {
        $post->load(['category', 'user']);

        // Increment view count
        $post->increment('views');

        return view('posts.show', compact('post'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Post $post)
    {
        $categories = Category::orderBy('name')->get();
        return view('posts.edit', compact('post', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePostRequest $request, Post $post)
    {
        $data = $request->validated();

        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image
            if ($post->image) {
                Storage::disk('public')->delete($post->image);
            }

            $image = $request->file('image');
            $imageName = time() . '_' . Str::slug($data['title']) . '.' . $image->getClientOriginalExtension();
            $imagePath = $image->storeAs('posts', $imageName, 'public');
            $data['image'] = $imagePath;
        }

        $post->update($data);

        return redirect()->route('posts.index')
            ->with('success', 'Post updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        // Delete image if exists
        if ($post->image) {
            Storage::disk('public')->delete($post->image);
        }

        $post->delete();

        return redirect()->route('posts.index')
            ->with('success', 'Post deleted successfully.');
    }

    /**
     * Toggle like for a post.
     */
    public function toggleLike(Post $post)
    {
        // This is a simple implementation - in a real app you'd want a likes table
        $post->increment('likes');

        return response()->json([
            'likes' => $post->likes,
            'message' => 'Post liked successfully.'
        ]);
    }

    /**
     * API: Display a listing of posts.
     */
    public function apiIndex(Request $request)
    {
        $query = Post::with(['category', 'user']);

        // Filter by category if provided
        if ($request->has('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        // Search functionality
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('subtitle', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%")
                  ->orWhere('body', 'like', "%{$search}%");
            });
        }

        // Sort options
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        // Pagination
        $perPage = $request->get('per_page', 30);
        $posts = $query->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $posts,
            'message' => 'Posts retrieved successfully.'
        ]);
    }

    /**
     * API: Display the specified post.
     */
  public function apiShow(Post $post)
{
    $post->load(['category', 'user']);

    // Increment view count
    $post->increment('views');

    // Suggested posts from the same category (exclude current one)
    $suggested = Post::with(['category', 'user'])
        ->where('category_id', $post->category_id)
        ->where('id', '!=', $post->id)
        ->latest()
        ->take(6)
        ->get();

    return response()->json([
        'success' => true,
        'data' => [
            'post' => $post,
            'suggested' => $suggested,
        ],
        'message' => 'Post retrieved successfully.'
    ]);
}


    /**
     * API: Toggle like for a post.
     */
    public function apiToggleLike(Post $post)
    {
        $post->increment('likes');

        return response()->json([
            'success' => true,
            'data' => [
                'likes' => $post->likes
            ],
            'message' => 'Post liked successfully.'
        ]);
    }

    // apiSearch apiView
    public function apiSearch(Request $request)
    {
        $search = $request->search;
        $posts = Post::where('title', 'like', "%{$search}%")
                ->orWhere('subtitle', 'like', "%{$search}%")
                ->orWhere('code', 'like', "%{$search}%")
                ->orWhere('body', 'like', "%{$search}%")
            ->with(['category', 'user'])->get();
        return response()->json([
            'success' => true,
            'data' => $posts,
            'message' => 'Posts retrieved successfully.'
        ]);
    }

    public function apiView(Post $post)
    {
        $post->load(['category', 'user']);
        $post->increment('views');
        return response()->json([
            'success' => true,
            'data' => $post,
            'message' => 'Post retrieved successfully.'
        ]);
    }
}
