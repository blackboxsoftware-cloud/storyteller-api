<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\BlogPostResource;
use App\Models\BlogPost;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Str;

class BlogPostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $perPage = request()->get('per_page', 10);
        $status = request()->get('status');

        $query = BlogPost::query();

        if ($status === 'all') {
            $query->whereIn('status', ['published', 'draft']);
        } elseif ($status) {
            $query->where('status', $status);
        } else {
            // No status supplied, default to published
            $query->where('status', 'published');
        }

        $posts = $query->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $posts
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'title' => 'required|string',
                'excerpt' => 'required|string|max:500',
                'category' => 'nullable|string',
                'tags' => 'nullable|array',
                'featured_image' => 'nullable|string',
                'body' => 'required|string',
                'status' => 'nullable|string|in:draft,published,archived'
            ]);

            // $images = [
            //     'https://images.unsplash.com/photo-1456513080510-7bf3a84b82f8?q=80&w=800&auto=format&fit=crop',
            //     'https://images.unsplash.com/photo-1447023029226-ef8f6b52e3ea?q=80&w=800&auto=format&fit=crop',
            //     'https://images.unsplash.com/photo-1434030216411-0b793f4b4173?q=80&w=800&auto=format&fit=crop',
            // ];

            $post = new BlogPost();
            $post->id = Str::uuid();
            $post->user_id = auth()->id();
            $post->title = $validated['title'];
            $post->slug = Str::slug($validated['title']) . '-' . Str::random(5);
            $post->body = $validated['body'];
            $post->excerpt = $validated['excerpt'];
            $post->featured_image = $validated['featured_image']; //$images[array_rand($images)];
            $post->tags = is_array($validated['tags']) ? json_encode($validated['tags']) : $validated['tags'];
            $post->status = $validated['status'];
            $post->category = $validated['category'];
            $post->published_at = $validated['status'] === 'published' ? now() : null ;
            $post->save();

            return response()->json([
                'message' => 'Blog Post Created Successfully',
                'data' => $post,
            ], 200);
        }catch (ValidationException $e) {
            Log::error('Blog Post error', ['error' => $e->getMessage()]);
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Throwable $e) {
            Log::error('Blog Post error', ['error' => $e->getMessage()]);
            return response()->json([
                'message' => 'Something went wrong',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($slug)
    {
        $blog_post = BlogPost::where('slug', $slug)->first();

        if (!$blog_post) {
            return response()->json([
                'message' => 'Blog Post not found',
                'data' => null,
            ], 404);
        }

        return response()->json([
            'message' => 'Blog Post Retrieved Successfully',
            'data' => new BlogPostResource($blog_post),
        ]);
    }

    /**
     * Change the status of a blog post.
     */
    public function changeStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|string|in:draft,published,archived'
        ]);

        $post = BlogPost::find($id);

        if (!$post) {
            return response()->json([
                'message' => 'Blog post not found'
            ], 404);
        }

        $post->status = $request->status;
        $post->published_at = $request->status === 'published' ? now() : null;
        $post->save();

        return response()->json([
            'message' => 'Blog post status updated successfully',
            'data' => $post
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $validated = $request->validate([
                'title' => 'required|string',
                'excerpt' => 'required|string|max:500',
                'category' => 'nullable|string',
                'tags' => 'nullable|array',
                'featured_image' => 'nullable|string',
                'body' => 'required|string',
                'status' => 'nullable|string|in:draft,published,archived'
            ]);

            // $images = [
            //     'https://images.unsplash.com/photo-1456513080510-7bf3a84b82f8?q=80&w=800&auto=format&fit=crop',
            //     'https://images.unsplash.com/photo-1447023029226-ef8f6b52e3ea?q=80&w=800&auto=format&fit=crop',
            //     'https://images.unsplash.com/photo-1434030216411-0b793f4b4173?q=80&w=800&auto=format&fit=crop',
            // ];

            $post = BlogPost::findOrFail($id);

            $post->title = $validated['title'];
            $post->slug = Str::slug($validated['title']) . '-' . Str::random(5);
            $post->body = $validated['body'];
            $post->excerpt = $validated['excerpt'];
            $post->featured_image = $validated['featured_image']; //$images[array_rand($images)];
            $post->tags = is_array($validated['tags']) ? json_encode($validated['tags']) : $validated['tags'];
            $post->status = $validated['status'];
            $post->category = $validated['category'];
            $post->save();

            return response()->json([
                'message' => 'Blog Post Updated Successfully',
                'data' => $post,
            ], 200);
        }catch (ValidationException $e) {
            Log::error('Blog Post Update error', ['error' => $e->getMessage()]);
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Throwable $e) {
            Log::error('Blog Post Update error', ['error' => $e->getMessage()]);
            return response()->json([
                'message' => 'Something went wrong',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $post = BlogPost::findOrFail($id);
        $post->delete();

        return response()->json([
            'message' => 'Blog Post Deleted Successfully',
        ], 200);
    }
}
