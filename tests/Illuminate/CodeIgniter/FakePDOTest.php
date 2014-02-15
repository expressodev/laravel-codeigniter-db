<?php

namespace Illuminate\CodeIgniter;

use Mockery as m;

class FakePDOTest extends \PHPUnit_Framework_TestCase
{
    public function testConstants()
    {
        $this->assertSame(2, FakePDO::FETCH_ASSOC);
        $this->assertSame(5, FakePDO::FETCH_OBJ);
    }
}
