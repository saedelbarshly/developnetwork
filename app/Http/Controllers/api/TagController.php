<?php

namespace App\Http\Controllers\api;

use App\Models\Tag;
use Illuminate\Http\Request;
use App\Http\Requests\TagReqeust;
use App\Http\Resources\TagResource;
use App\Http\Controllers\Controller;

class TagController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tags = auth()->user()?->tags()->paginate(10);
        return TagResource::collection($tags)->response()->getData(true);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(TagReqeust $request)
    {
        try {
            Tag::create(['name' => $request->name, 'post_id'=>$request->post_id]);
            return response()->json(['status' => true, 'message' => "Tag Created Successfully ✅"]);
        } catch (\Throwable $th) {
            return response()->json(['status' => false,'message' => "Someting went wrong!"]);
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
     * Update the specified resource in storage.
     */
    public function update(TagReqeust $request, Tag $tag)
    {
        try {
            $tag->update([
                'name' => $request->name,
                'post_id' => $request->post_id,
            ]);
            return response()->json(['status' => true, 'message' => "Tag Created Successfully ✅"]);
        } catch (\Throwable $th) {
            return response()->json(['status' => false,'message' => "Someting went wrong!"]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Tag $tag)
    {
        try {
            $tag->delete();
            return response()->json(['status' => true, 'message' => "Post Deleted Successfully ✅"]);
        } catch (\Throwable $th) {
            return response()->json(['status' => false,'message' => "Someting went wrong!"]);
        }
    }
}
