<?php

namespace Illuminate\CodeIgniter;

use Mockery as m;

class CodeIgniterConnectionTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->ci = m::mock('ci');
        $this->ci->db = m::mock('ci_db');
        $this->ci->db->dbprefix = 'zzz_';
        $this->ci->db->dbdriver = 'mysql';

        $this->connection = new CodeIgniterConnection($this->ci);
    }

    public function testConstruct()
    {
        $this->assertSame('zzz_', $this->connection->getTablePrefix());
    }
}
