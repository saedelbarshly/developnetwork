<?php

namespace App\Http\Controllers\api;

use App\Models\Post;
use App\Traits\ImageTrait;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\PostResource;

class PostController extends Controller
{
    use ImageTrait;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $posts = auth()->user()->posts;
        return PostResource::collection($posts)->response()->getData(true);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $data = $request->except(['_token','cover_image']);
            if($request->hasFile('cover_image')){
                $data['cover_image'] = $this->upload_image($request->file('cover_image'));
            }
            $post = auth()->user()->posts()->create($data);
            return response()->json(['status' => true, 'message' => "Post Created Successfully ✅"]);
        } catch (\Throwable $th) {
            return response()->json(['status' => false,'message' => "Someting went wrong!"]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Post $post)
    {
        try {
            return new PostResource($post);
        } catch (\Throwable $th) {
            return response()->json(['status' => false,'message' => "Someting went wrong!"]);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Post $post)
    {
        try {
            $data = $request->except(['_token','cover_image']);
            if($request->hasFile('cover_image')){
                $this->delete_image($post->cover_image);
                $data['cover_image'] = $this->upload_image($request->file('cover_image'));
            }
            $post->update($data);
            return response()->json(['status' => true, 'message' => "Post Updated Successfully ✅"]);
        } catch (\Throwable $th) {
            return response()->json(['status' => false,'message' => "Someting went wrong!"]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        try {
            $post->delete();
            return response()->json(['status' => true, 'message' => "Post Deleted Successfully ✅"]);
        } catch (\Throwable $th) {
            return response()->json(['status' => false,'message' => "Someting went wrong!"]);
        }
    }

    public function trash()
    {
        try {
            $posts = auth()->user()?->posts()?->onlyTrashed()->get();
            return PostResource::collection($posts)->response()->getData(true);
        } catch (\Throwable $th) {
            return response()->json(['status' => false,'message' => "Someting went wrong!"]);
        }
    }

    public function restore($id)
    {
        try {
            $post = Post::withTrashed()->find($id);
            $post->restore();
            return response()->json(['status' => true, 'message' => "Post Restored Successfully ✅"]);
        } catch (\Throwable $th) {
            return response()->json(['status' => false,'message' => "Someting went wrong!"]);
        }
    }


    public function pin(Post $post)
    {
        try {
            $post->update(['pinned' => 1]);
            return response()->json(['status' => true, 'message' => "Post Pinned Successfully ✅"]);
        } catch (\Throwable $th) {
            return response()->json(['status' => false,'message' => "Someting went wrong!"]);
        }
    }
}
