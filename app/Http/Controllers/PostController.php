<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use App\Models\Post;

class PostController extends Controller {
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

    return redirect("/post/{$newPost->id}")->with('success', 'New post created!');
  }

  public function viewSinglePost(Post $post)
  {
    $post->body = strip_tags(Str::markdown($post->body));
    return view('single-post', ['post' => $post]);
  }

  public function delete(Post $post)
  {
    if(!Auth::user()->can('delete', $post)) {
      return 'You cannot do that';
    }

    $post->delete();
    return redirect('/profile/' . Auth::user()->username)->with('success', 'Post successfully deleted.');
  }
}