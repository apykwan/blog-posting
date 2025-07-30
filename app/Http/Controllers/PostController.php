<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
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
    if(!Auth::user()->can('delete', $post)) {
      return 'You cannot do that';
    }

    $post->delete();
    return redirect('/profile/' . Auth::user()->username)->with('success', 'Post successfully deleted.');
  }
}