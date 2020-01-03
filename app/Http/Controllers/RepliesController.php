<?php

namespace App\Http\Controllers;

use App\User;
use App\Reply;
use App\Thread;
use App\Inspections\Spam;
use Illuminate\Support\Facades\Gate;
use App\Notifications\YouWereMentioned;
use App\Http\Requests\CreatePostRequest;

class RepliesController extends Controller
{
    /**
     * Create a new RepliesController instance.
     */
    public function __construct()
    {
        $this->middleware('auth',
            ['except' => 'index']# make index act like API so it doesn't need auth
        )
        ;
    }

    /**
     * Persist a new reply.
     *
     * @param  integer $channelId
     * @param  Thread  $thread
     * @return \Illuminate\Http\RedirectResponse
     */

    /**
     * Fetch all relevant replies.
     *
     * @param int    $channelId
     * @param Thread $thread
     */
    public function index($channelId, Thread $thread)
    {
        return $thread->replies()->paginate(20);
    }

    public function store($channelId, Thread $thread, CreatePostRequest $form)
    {
        if ($thread->locked) {
            return response('Thread is locked', 422);
        }

        return $thread->addReply([
            'body'    => request('body'),
            'user_id' => auth()->id(),
        ])->load('owner');

        // inspect the body of the reply for username mentions
        // preg_match_all('/\@([^\s\.]+)/', $this->body, $matches);

        // $names = $matches[1];

        // // foreach mentioned user, notify them
        // foreach ($names as $name) {
        //     $user = User::whereName($name)->first();

        //     if ($user) {
        //         $user->notify(new YouWereMentioned);
        //     }
        // }

    }

    public function destroy(Reply $reply)
    {
        $this->authorize('update', $reply);

        $reply->delete();

        if (request()->expectsJson()) {
            return response(['status' => 'Reply deleted']);
        }
        return back();
    }

    /**
     * Update an existing reply.
     *
     * @param Reply $reply
     */
    public function update(Reply $reply)
    {
        $this->authorize('update', $reply);

        $this->validate(request(), ['body' => 'required|spamfree']);
        $reply->update(request(['body']));

    }

    /**
     * Validate the incoming reply.
     */
    // protected function validateReply()
    // {
    //     $this->validate(request(), ['body' => 'required|spamfree']);
    //     // resolve(Spam::class)->detect(request('body'));
    // }

}
