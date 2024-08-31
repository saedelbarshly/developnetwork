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

}
