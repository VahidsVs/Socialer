<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Follow;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FollowController extends Controller
{
    public function createFollow(User $user)
    {
        if ($user->id == Auth::user()->id) {
            return back()->with("failure", "You cannot follow yourself");
        }
        $existCheck = Follow::where([["user_id", auth()->user()->id], ["followed_user", $user->id]])->count();
        if ($existCheck) {
            return back()->with("failure", "You have already followed this user");
        }
        $follow = new Follow();
        $follow->user_id = Auth::user()->id;
        $follow->followed_user = $user->id;
        $follow->save();
        //$followData=["user_id"=>Auth::user()->id,"followed_user"=>$user->id];
       //Follow::create($followData);
        return back()->with("success", "User successfuly followed");
    }

    public function deleteFollow(User $user)
    {
        Follow::where([["user_id", auth()->user()->id], ["followed_user", $user->id]])->delete();
        return back()->with("success", "User successfuly unfollowed");
    }
    //
}
