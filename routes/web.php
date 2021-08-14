<?php

use App\Category;
use App\Tag;
use App\Comment;
use App\Post;
use App\User;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {

    // Lazy loading method
    // $categories = Category::select('id', 'title')->orderBy('title')->get();

    // Get the most use tag by count tag in pivot table
    // $tags = Tag::select('id', 'name')->orderByDesc(
    //     DB::table('post_tag')
    //     ->selectRaw('count(tag_id) as tag_count')
    //     ->whereColumn('tags.id', 'post_tag.tag_id')
    //     ->orderBy('tag_count', 'desc')
    //     ->limit(1)
    // )->get();

    $mostPopularPosts = Post::select('id', 'title')->orderByDesc(
        Comment::selectRaw('count(post_id) as comment_count')
        ->whereColumn('posts.id', 'comments.post_id')
        ->orderBy('comment_count', 'desc')
        ->limit(1)
    )
    ->withCount('comments')->take(5)->get();

    // Most active user is who write many posts
    $mostActiveUsers = User::select('id', 'name')->orderByDesc(
        Post::selectRaw('count(user_id) as post_count')
        ->whereColumn('users.id', 'posts.user_id')
        ->orderBy('post_count', 'desc')
        ->limit(1)
    )
    ->withCount('posts')->take(5)->get();

    // Most popular category
    $mostPopularCategory = Category::select('id', 'title')
    ->withCount('comments')
    ->orderBy('comments_count', 'desc')
    ->take(1)->get();

    //Search for blog posts in standard way - LIKE operator
    // $postTitle = 'Voluptatibus';
    // $postContent = 'Quidem';

    // $results = DB::table('posts')
    // ->where('title', 'like', "%$postTitle%")
    // ->orWhere('content', 'like', "%$postContent%")
    // ->get();

    // Search blog posts using fulltext index
    $searchTerm = 'Voluptatibus';
    $results = DB::table('posts')
   ->whereRaw("MATCH(title, content) AGAINST(? IN BOOLEAN MODE)",
   [$searchTerm])
    ->get();

    //

    dd($results);
    return view('welcome');
});
