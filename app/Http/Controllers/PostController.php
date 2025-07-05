<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use App\Models\{User, Post};

class PostController extends Controller {
  public function showCreateForm() 
  {
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

    return redirect("/post/{$newPost->id}")->with('success', 'New post created!');
  }

  public function viewSinglePost(Post $post)
  {
    $username = User::find($post->user_id)->username;

    return view('single-post', ['post' => $post, 'username' => $username]);
  }
}