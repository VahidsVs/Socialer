<?php

namespace App\Http\Controllers;

use view;
use App\Models\Post;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Mail\NotificationMail;
use App\Jobs\SendNotificationMail;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\FieldsRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class PostController extends Controller
{
    public function search($term)
    {
        $posts = Post::search($term)->get();
        $posts->load('user:id,username,avatar'); // to load and shown json data of relationships table
        return $posts;
    }
    public function updatePost(Post $post, Request $request)
    {
        $values = $request->validate([
            "title" => ["required"],
            "body" => ["required"]
        ]);
        $values["title"] = strip_tags($values["title"]);
        $values["body"] = strip_tags($values["body"]);
        $post->update($values);
        return back()->with("success", "the post is successfully created");
    }

    public function viewEditPost(Post $post)
    {
        return view("view-edit-post", ["post" => $post]);
    }
    public function deletePost(Post $post)
    {
        //if(auth()->user()->cannot("delete",$post))
        //return redirect("profile/".auth()->user()->username)->with("failure","You dont have permission to do that");
        $post->delete();
        return redirect("profile/" . auth()->user()->username)->with("success", "post successfully deleted.");
    }
    public function deletePostApi(Post $post)
    {
        return $post->delete();
    }
    public function showCreatePost()
    {
        return view("create-post");
    }
    public function viewPost(Post $post)
    {
        $post["body"] = strip_tags(Str::markdown($post->body), "<p><ul><li><h4><strong>");
        return  view("view-post", ["post" => $post]);
    }
    public function createPostApi(FieldsRequest $request)
    {
        $values=$request->validate(["title"=>["required"],"body"=>"required"]);
        $values["user_id"] = auth()->id();
        $newPost = Post::create($values);
        //below: sending mail asyncronous in background
        dispatch(new SendNotificationMail(['sendTo' => Auth::user()->email, 'subject' => 'Create new post', 'username' => Auth::user()->username, 'title' => $values["title"]]));
        //Below: sending mail syncronous
        //Mail::to(Auth::user()->email)->send(new NotificationMail(['subject'=>'Create new post','username'=>Auth::user()->username,'title'=>$values["title"]]));
        return $newPost->id;
    }
    public function createPost(FieldsRequest $request)
    {
        // $values = $request->validate([
        //     "title" => ["required"],
        //     "body" => ["required"]
        // ]);


        $values["title"] = strip_tags($request["title"]);
        $values["body"] = strip_tags($request["body"]);
        $values["user_id"] = auth()->id();
        $newPost = Post::create($values);
        //below: sending mail asyncronous in background
        dispatch(new SendNotificationMail(['sendTo' => Auth::user()->email, 'subject' => 'Create new post', 'username' => Auth::user()->username, 'title' => $values["title"]]));
        //Below: sending mail syncronous
        //Mail::to(Auth::user()->email)->send(new NotificationMail(['subject'=>'Create new post','username'=>Auth::user()->username,'title'=>$values["title"]]));
        return redirect("/view-post/{$newPost->id}")->with("success", "the post is successfully created");
    }

    //
}
