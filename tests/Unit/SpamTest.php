<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class SpamTest extends TestCase
{
    /** @test */
    public function it_checks_for_invalid_keywords()
    {
        $spam = new Spam();
        $this->assertFalse($spam->detect('Innocent reply here'));
        $spam->detect('yahoo customer support');
        $this->expectException('Exception');
    }
    /** @test */
    function it_checks_for_any_key_being_held_down()
    {
        $spam = new Spam();
        $this->expectException('Exception');
        $spam->detect('Hello world aaaaaaaaa');
    }
}
