@extends('layout')

@section('content')
    <h1 class="text-center">Welcome {{ $user->name }} to DishBook</h1>

    <div class="container">
        <div class="row justify-content-center">
            <!-- Dashboard Card -->
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('Dashboard') }}</div>
                    <div class="card-body">
                        @if (session('success'))
                            <div class="alert alert-success" role="alert">
                                {{ session('success') }}
                            </div>
                        @endif
                        <!-- Display user information or recent activities -->
                        <p>You are Logged In</p>
                    </div>
                    
                    <!-- Post Form -->
                    <div class="card">
                        <div class="card-header">{{ __('Create Post') }}</div>
                        <div class="card-body">
                            <form action="{{ route('social.createPost') }}" method="POST">
                                @csrf
                                <div class="form-group">
                                    <textarea name="content" rows="3" class="form-control" placeholder="What's on your mind?"></textarea>
                                </div>
                                <button type="submit" class="btn btn-primary">Post</button>
                            </form>
                        </div>
                    </div>

                    <!-- Display Posts -->
                    <div class="card">
                        <div class="card-header">{{ __('Posts') }}</div>
                        <div class="card-body">
                            @forelse ($posts as $post)
                            <div class="bg-light mb-5 p-2">
                                <div style="display: flex" class="justify-content-between">
                                    <strong>{{ $post->user->name }}</strong>
                                    <p>{{ $post->created_at->diffForHumans() }}</p>
                                </div>
                                <div class="bg-white w-75 m-auto px-2">
                                    <p>{{ $post->content }}</p>
                                </div>
                                 <!-- Likes -->
                                 <div style="display: flex" class="justify-content-between">
                                    @php
                                    $userLikedPost = $post->likes->contains('user_id', auth()->user()->id);
                                    @endphp
                            
                                    @if($userLikedPost)
                                        <!-- Unlike button -->
                                        <form action="{{ route('social.unlikePost', ['postId' => $post->id]) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger">Unlike</button>
                                        </form>
                                    @else
                                        <!-- Like button -->
                                        <form action="{{ route('social.likePost', ['postId' => $post->id]) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-outline-primary">Like</button>
                                        </form>
                                    @endif
                                    <span>{{ $post->likes->count() }} Likes</span>
                                    <span>{{ $post->comments->count() }} comments</span>
                                 </div>

                                <!-- Comments -->
                                <div class="mt-2">
                                    <p><strong>Comments:</strong></p>
                                    @forelse ($post->comments as $comment)
                                        {{-- Check if the authenticated user is the commenter, a follower of the post owner, or the post owner --}}
                                        @if($comment->user && (auth()->check() && ($comment->user->id === auth()->id() || $post->user->followers->contains(auth()->id()) || $post->user->id === auth()->id())))
                                            <p>{{ $comment->user->name }}: {{ $comment->content }}</p>
                                        @endif
                                    @empty
                                        <p>No comments yet.</p>
                                    @endforelse
                                </div>

                                <!-- Add a comment -->
                                <form action="{{ route('social.addComment', ['postId' => $post->id]) }}" method="POST">
                                    @csrf
                                    <div class="form-group">
                                        <input type="text" name="content" class="form-control" placeholder="Add a comment">
                                    </div>
                                    <button type="submit" class="btn btn-sm btn-outline-secondary">Comment</button>
                                </form>
                                <!-- Add more details or interaction options if needed -->
                            </div>
                            <hr>
                            @empty
                            <p>No posts available.</p>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>

            <!-- Followers Card -->
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">{{ __('Followers') }}</div>
                    <div class="card-body">
                        @forelse ($followers as $follower)
                            <p>{{ $follower->name }}</p>
                        @empty
                            <p>No followers yet.</p>
                        @endforelse
                    </div>  
                </div>

                <!-- Following Card -->
                <div class="card mt-4">
                    <div class="card-header">{{ __('Following') }}</div>
                    <div class="card-body">
                        @forelse ($user->followings as $following)
                            <p>{{ $following->name }}</p>
                            <form action="{{ route('social.unfollowUser', ['userId' => $following->id]) }}" method="POST" onsubmit="return confirm('Are you sure you want to unfollow?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">Unfollow</button>
                            </form>
                        @empty
                            <p>You are not following anyone yet.</p>
                        @endforelse
                    </div>
                </div>

                <!-- Other Users Card -->
                <div class="card mt-4">
                    <div class="card-header">{{ __('Other Users') }}</div>
                    <div class="card-body">
                        @forelse ($users as $otherUser)
                            @if (!$user->followings->contains($otherUser))
                                <p>{{ $otherUser->name }}</p>
                                <form action="{{ route('social.followUser', ['userId' => $otherUser->id]) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-success">Follow</button>
                                </form>
                            @endif
                        @empty
                            <p>No other users found.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
