<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class MentionUsersTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function mentioned_users_in_a_reply_are_notified()
    {
        // Give I have a user Johndoe, who is signed in.
        $john = create('App\User', ['name' => 'JohnDoe']);

        $this->signIn($john);

        // And another uers, JaneDoe
        $jane = create('App\User', ['name' => 'JaneDoe']);

        // When we have a thread
        $thread = create('App\Thread');

        // And JohnDoe replies to that thread and mentions @JaneDoe.
        $reply = make('App\Reply', [
            'body' => 'Hey @JaneDoe check this out.',
        ]);

        $this->json('post', $thread->path() . '/replies',
            $reply->toArray());

        // Then @JaneDoe should receive a notification
        $this->assertCount(1, $jane->notifications);

    }

    /** @test */
    public function it_can_fetch_all_mentioned_users_starting_with_the_given_characters()
    {
        create('App\User', ['name' => 'johndoe']);
        create('App\User', ['name' => 'johndoe2']);
        create('App\User', ['name' => 'janedoe']);

        $results = $this->json('GET', '/api/users', ['name' => 'john']);

        $this->assertCount(2, $results->json());
    }

}
