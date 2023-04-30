<?php

namespace Tests\Helpers;

use Tests\TestCase;

class TeHelperTest extends TestCase
{
    /**
     * Test the helper function.
     *
     * @return void
     */
    public function testWillExpireAt()
    {
        $dueTime = Carbon::now()->addHours(10);
        $createdAt = Carbon::now();

        $result = willExpireAt($dueTime, $createdAt);

        // Assert that the result is what we expect
        $this->assertEquals($dueTime->format('Y-m-d H:i:s'), $result);
    }
}
