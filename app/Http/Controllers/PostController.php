<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Auth\Access\AuthorizationException;
use App\Models\Post;
use App\Jobs\SendNewPostEmail;

class PostController extends Controller {
  public function search($term)
  {
    return Post::search($term)
      ->get()
      ->load('user:id,username,avatar');
  }

  public function showCreateForm() 
  {
    if (!Auth::check()) {
      return redirect('/');
    }
    return view('create-post');
  }

  public function storeNewPost(Request $request)
  {
    $incomingFields = $request->validate([
      'title' => 'required',
      'body' => 'required'
    ]);

    $incomingFields['title'] = strip_tags($incomingFields['title']);
    $incomingFields['body'] = strip_tags($incomingFields['body']);
    $incomingFields['user_id'] = Auth::id();
    
    $newPost = Post::create($incomingFields);

    dispatch(new SendNewPostEmail([
      'sendTo' => Auth::user()-> email,
      'name' => Auth::user()->username,
      'title' => $newPost->title
    ]));
    
    return redirect("/post/{$newPost->id}")->with('success', 'New post created!');
  }

  public function showEditForm(Post $post)
  {
    return view('edit-post', ['post' => $post]);
  }

  public function actuallyUpdate(Post $post, Request $request)
  {
    $incomingFields = $request->validate([
      'title' => 'required',
      'body' => 'required'
    ]);

    $incomingFields['title'] = strip_tags($incomingFields['title']);
    $incomingFields['body'] = strip_tags($incomingFields['body']);

    $post->update($incomingFields);

    return back()->with('success', 'Post successfully updated');
  }

  public function viewSinglePost(Post $post)
  {
    $post->body = strip_tags(Str::markdown($post->body));
    return view('single-post', ['post' => $post]);
  }

  public function delete(Post $post)
  {
    $post->delete();
    return redirect('/profile/' . Auth::user()->username)->with('success', 'Post successfully deleted.');
  }

  public function storeNewPostApi(Request $request)
  {
    try {
      $incomingFields = $request->validate([
        'title' => 'required',
        'body' => 'required'
      ]);

      $incomingFields['title'] = strip_tags($incomingFields['title']);
      $incomingFields['body'] = strip_tags($incomingFields['body']);
      $incomingFields['user_id'] = Auth::id(); // Possible point of failure

      $newPost = Post::create($incomingFields); // Possible point of failure

      dispatch(new SendNewPostEmail([
        'sendTo' => Auth::user()->email,
        'name' => Auth::user()->username,
        'title' => $newPost->title
      ]));

      return response()->json(['postId' => $newPost->id]);
    } catch (\Throwable $e) {
      return response()->json([
        'error' => $e->getMessage()
      ], 500);
    }
  }

  public function deleteApi(Post $post)
  {
    if (Gate::denies('deletePost', $post)) {
      return response()->json(['message' => 'You are not authorized to delete this post.'], 403);
    }

    $post->delete();

    return response()->json(['postId' => $post->id]);
  }
}