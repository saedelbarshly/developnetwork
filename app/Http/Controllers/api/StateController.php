<?php

namespace App\Http\Controllers\api;

use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;

class StateController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $stats = Cache::remember('states', now()->addMinutes(10), function () {
            $totalUsers = User::count();
            $totalPosts = Post::count();
            $usersWithNoPosts = User::doesntHave('posts')->count();

            return [
                'total_users' => $totalUsers,
                'total_posts' => $totalPosts,
                'users_with_no_posts' => $usersWithNoPosts,
            ];
        });

        return response()->json($stats);
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
}
