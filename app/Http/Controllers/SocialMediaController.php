<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Post;
use App\Models\Like;
use App\Models\Comment;
use App\Models\Follow;
use App\Models\User;


class SocialMediaController extends Controller
{
    public function index()
    {
        // Check if the user is authenticated
        $user = auth()->user();
    
        if (!$user) {
            // Handle the case where the user is not authenticated
            return redirect()->route('login');
        }
    
        // Fetch followers and followings with eager loading
        $user = User::with('followers', 'followings')->find($user->id);
    
        // Fetch other users
        $users = User::where('id', '!=', $user->id)->get();
    
        // Eager load likes and comments relationships for each post
        $posts = $user->posts()->with(['user', 'likes', 'comments'])->latest()->get();
    
        // Fetch posts from people the user is following
        foreach ($user->followings as $following) {
            $posts = $posts->merge($following->posts()->with(['user', 'likes', 'comments'])->latest()->get());
        }
    
        // Remove duplicate posts (if any)
        $posts = $posts->unique('id');
    
        // Define $followers variable
        $followers = $user->followers;
    
        // Use $followers in compact
        return view('social.index', compact('user', 'followers', 'users', 'posts'));
    }
    
    
    public function createPost(Request $request)
    {
        // Validate the input data
        $request->validate([
            'content' => 'required|max:255',
        ]);

        // Create the post
        auth()->user()->posts()->create([
            'content' => $request->input('content'),
        ]);

        return redirect()->route('social.index')->with('success', 'Post created successfully.');
    }

    
    // allowing you to follow other users
    public function followUser($userId)
    {
        try {
            // Validate user ID
            $userToFollow = User::findOrFail($userId);
    
            // Use Eloquent relationship to attach the follower
            auth()->user()->followings()->attach($userId);
    
            return redirect()->route('social.following')->with('success', 'You are now following ' . $userToFollow->name);
        } catch (\Exception $e) {
            // Handle the exception (e.g., log, display error message)
            return redirect()->back()->with('error', 'Failed to follow the user.');
        }
    }

     // allowing you to unfollow users
     public function unfollowUser($userId)
     {
        try {
        // Validate user ID
        $userToUnfollow = User::findOrFail($userId);
        
        // Use Eloquent relationship to detach the follower
        auth()->user()->followings()->detach($userId);

        return redirect()->route('social.unfollowUser')->with('success', 'You have unfollowed ' . $userToUnfollow->name);
        }
        catch(\Exception $e){
            // Handle the exception (e.g., log, display error message)
            return redirect()->back()->with('error', 'Failed to unfollow the user.');
            }
    }

    
    public function userProfile($userId)
    {
        $user = User::findOrFail($userId);
        $posts = $user->posts()->latest()->get();

        return view('social.user_profile', compact('user', 'posts'));
    }

    // view user that follow you
    public function userFollowers($userId)
    {
        $user = User::findOrFail($userId);
        $followers = $user->followers;

        return view('social.followers', compact('user', 'followers'));
    }

    // view user that you are following
    public function userFollowing($userId)
    {
        $user = User::findOrFail($userId);
        $following = $user->followings()->get();

        return view('social.following', compact('user', 'following'));
    }
    // Add methods for likes, comments, and other functionalities

    // Like a post
    public function likePost($postId)
    {
        try {
            $post = Post::findOrFail($postId);

            // Check if the user has already liked the post
            if (!$post->likes()->where('user_id', auth()->user()->id)->exists()) {
                // Create a like for the post
                $like = new Like();
                $like->user_id = auth()->user()->id;
                $post->likes()->save($like);

                return redirect()->route('social.index')->with('success', 'Post liked successfully.');
            } else {
                return redirect()->route('social.index')->with('error', 'You have already liked this post.');
            }
        } catch (\Exception $e) {
            // Handle the exception (e.g., log, display error message)
            return redirect()->back()->with('error', 'Failed to like the post.');
        }
    }

    public function unlikePost($postId)
    {
        try {
            $post = Post::findOrFail($postId);

            // Check if the user has liked the post
            $like = $post->likes()->where('user_id', auth()->user()->id)->first();

            if ($like) {
                // Delete the like
                $like->delete();

                return redirect()->route('social.index')->with('success', 'Post unliked successfully.');
            } else {
                return redirect()->route('social.index')->with('error', 'You have not liked this post.');
            }
        } catch (\Exception $e) {
            // Handle the exception (e.g., log, display error message)
            return redirect()->back()->with('error', 'Failed to unlike the post.');
        }
    }

    // Add a comment to a post
    public function addComment(Request $request, $postId)
    {
        try {
            $post = Post::findOrFail($postId);

            // Validate the input data
            $request->validate([
                'content' => 'required|max:255',
            ]);

            // Create a comment for the post
            $comment = new Comment();
            $comment->user_id = auth()->user()->id;
            $comment->content = $request->input('content');
            $post->comments()->save($comment);
            return redirect()->route('social.index')->with('success', 'Comment added successfully.');
        } catch (\Exception $e) {
            // Handle the exception (e.g., log, display error message)
            return redirect()->back()->with('error', 'Failed to add a comment.');
        }
    }


}
