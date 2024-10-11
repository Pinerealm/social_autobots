<?php

use App\Models\Autobot;
use Illuminate\Support\Facades\Route;
use App\Models\Post;
use App\Models\Comment;
use Illuminate\Http\Request;

/**
 * @OA\Get(
 *     path="/api/autobot-count",
 *     summary="Get the count of Autobots",
 *     @OA\Response(
 *         response=200,
 *         description="The count of Autobots"
 *     )
 * )
 */
Route::get('/autobot-count', function () {
    return response()->json([
        'count' => Autobot::count()
    ]);
});

/**
 * @OA\Get(
 *     path="/api/autobots",
 *     summary="Get a list of Autobots",
 *     @OA\Response(
 *         response=200,
 *         description="A list of Autobots"
 *     ),
 *     @OA\Parameter(
 *         name="page",
 *         in="query",
 *         description="Pagination page number",
 *         required=false,
 *         @OA\Schema(type="integer")
 *     )
 * )
 */
Route::get('/autobots', function (Request $request) {
    return Autobot::paginate(10);
});

/**
 * @OA\Get(
 *     path="/api/autobots/{id}/posts",
 *     summary="Get a list of posts by Autobot",
 *     @OA\Response(
 *         response=200,
 *         description="A list of posts by Autobot"
 *     ),
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         description="ID of the Autobot",
 *         required=true,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Parameter(
 *         name="page",
 *         in="query",
 *         description="Pagination page number",
 *         required=false,
 *         @OA\Schema(type="integer")
 *     )
 * )
 */
Route::get('/autobots/{id}/posts', function ($id) {
    $autobot = Autobot::findOrFail($id);
    return $autobot->posts()->paginate(10);
});

/**
 * @OA\Get(
 *     path="/api/posts/{id}/comments",
 *     summary="Get a list of comments by post",
 *     @OA\Response(
 *         response=200,
 *         description="A list of comments by post"
 *     ),
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         description="ID of the post",
 *         required=true,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Parameter(
 *         name="page",
 *         in="query",
 *         description="Pagination page number",
 *         required=false,
 *         @OA\Schema(type="integer")
 *     )
 * )
 */
Route::get('/posts/{id}/comments', function ($id) {
    $post = Post::findOrFail($id);
    return $post->comments()->paginate(10);
});

// Implementing rate limiting on the API routes
Route::middleware('throttle:5,1')->group(function () {
    Route::get('/autobots', function (Request $request) {
        return Autobot::paginate(10);
    });

    Route::get('/autobots/{id}/posts', function ($id) {
        $autobot = Autobot::findOrFail($id);
        return $autobot->posts()->paginate(10);
    });

    Route::get('/posts/{id}/comments', function ($id) {
        $post = Post::findOrFail($id);
        return $post->comments()->paginate(10);
    });
});
