<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class SubscribeToThreadsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_user_can_subscribe_to_threads()
    {
        $this->signIn();

        // Given we have a thread...
        $thread = create('App\Thread');

        // And the user subscribes to the thread...
        $this->post($thread->path() . '/subscriptions');


        // the $thread (freshly loaded) should have only one subscription
        $this->assertCount(1, $thread->fresh()->subscriptions);


        // $this->assertCount(0, auth()->user()->notifications);

        // // Then, each time a new reply is left...
        // $thread->addReply([
        //     'user_id' => auth()->id(),
        //     'body'    => 'Some reply here',
        // ]);

        // // A notification should be prepared for the user.
        // $this->assertCount(1, auth()->user()->fresh()->notifications);

    }

    /** @test */
    public function a_user_can_unsubscribe_from_threads()
    {
        $this->signIn();
        $thread = create('App\Thread');

        $thread->subscribe();
        $this->delete($thread->path() . '/subscriptions');
        $this->assertCount(0, $thread->subscriptions);
    }

}
