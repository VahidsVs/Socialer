<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Follow;
use App\Events\MyEvent;
use Illuminate\Http\Request;
use GuzzleHttp\Promise\Create;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    public function UploadAvatar(Request $request)
    {
        $request->validate(["avatar" => ["required", "image", "max:3000"]]);

        $compressedImg = Image::make($request->file("avatar"))->fit(120)->encode("jpg");

        $user = Auth::user();
        $fileName = $user->id . "-" . uniqid() . ".jpg";
        Storage::put("public/avatar/$fileName", $compressedImg); //save compressed image
        $oldAvatar = $user->avatar;
        $user->avatar = $fileName;
        $user->save();
        if ($oldAvatar != "/fallback-avatar.jpg") {
            Storage::delete(str_replace("/storage/", "public/", $oldAvatar)); // removes old avatar
        }
        return back()->with("success", "avatar successfully changed");
    }
    public function showAvatarForm()
    {
        return (view('view-manage-avatar'));
    }
    public function getSharedData($user)
    {
        $currentlyFollowing = 0;
        if (auth()->check())
            $currentlyFollowing = Follow::where([["user_id", auth()->user()->id], ["followed_user", $user->id]])->count();
        View::share("sharedData", [
            "currentlyFollowing" => $currentlyFollowing, "username" => $user->username,
            "avatar" => $user->avatar, "postCount" => $user->posts()->count(), "followerCount" => $user->followers()->count(),
            "followingCount" => $user->followings()->count()
        ]);
    }
 
    public function viewProfileRaw(User $user)
    {

        return response()->json(["theHTML"=> view("view-profile-only" ,["posts"=> $user->posts()->latest()->get()])->render(),"docTitle"=>"$user->username's Profile"]);
    }
    public function viewProfileFollowersRaw(User $user)
    {

        return response()->json(["theHTML"=> view("view-profile-followers-only" ,["followers"=> $user->followers()->latest()->get()])->render(),"docTitle"=>"$user->username's Followers"]);
    }
    public function viewProfileFollowingsRaw(User $user)
    {
        return response()->json(["theHTML"=> view("view-profile-followings-only" ,["followings"=> $user->followings()->latest()->get()])->render(),"docTitle"=>"$user->username's Followings"]);
    }
    public function viewProfile(User $user)
    {
        //dd($user->posts()->latest()->get());
        //dd(User::with("posts")->where($user)->get());
        self::getSharedData($user);
        return view("view-profile", ["posts" => $user->posts()->latest()->get()]);
    }
    public function viewProfileFollowers(User $user)
    {
        self::getSharedData($user);
        return view("view-profile-followers", ["followers" => $user->followers()->latest()->get()]);
    }
    public function viewProfileFollowings(User $user)
    {
        self::getSharedData($user);
        return view("view-profile-followings", ["followings" => $user->followings()->latest()->get()]);
    }

    public function showHomepage()
    {

        if (auth()->check()) {
            $lastestFeeds = Auth::user()->feedPost()->latest()->paginate(4);
            $lastestFeeds->load('user:id,username,avatar'); // to load and shown json data of relationships table
            return view("homepage-loggedin", ["posts" => $lastestFeeds]);
        } else
            return view("homepage");
        //auth
    }
    public function login(Request $request)
    {
        $values = $request->validate(["loginusername" => "required", "loginpassword" => "required"]);
        if (auth()->attempt(["username" => $values["loginusername"], "password" => $values["loginpassword"]])) {
            $request->session()->regenerate();
            event(new MyEvent(['username'=>Auth::user()->username,'action'=>'login']));
            return redirect("/")->with("success", "you have successfully logged in");
        } else {
            $now = Carbon::now();
            return redirect("/")->with("failure", "invalid login at $now");
        }
    }
    public function logout()
    {
        event(new MyEvent(['username'=>Auth::user()->username,'action'=>'logout']));
        auth()->logout();
        return redirect("/")->with("success", "you have successfully logged out");;
    }
    //
    public function register(Request $request)
    {
        $values = $request->validate([
            "username" => ["required", "min:4", "max:20", Rule::unique("users", "username")],
            "email" => ["required", "email", Rule::unique("users", "email")],
            "password" => ["required", "min:8", "confirmed"]
        ]);
        // if($validator->fails())
        //     return response()->json(['errors'=>$validator->messages()]);
        //$values["password"]=bcrypt($values["password"]);
        $user = User::create($values);
        auth()->login($user);
        return redirect("/")->with("success", "you have successfully registered");
    }
}
