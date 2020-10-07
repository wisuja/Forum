<?php

namespace Tests\Unit;

use App\Models\Spam;
use Tests\TestCase;

class SpamTest extends TestCase
{
    public function test_it_validates_spam()
    {
        $spam = new Spam();

        $this->assertFalse($spam->detect('Innocent reply'));
    }
}
