<?php

namespace Illuminate\CodeIgniter;

use Illuminate\Database\ConnectionResolverInterface;

class CodeIgniterConnectionResolver implements ConnectionResolverInterface
{
    protected $ci;
    protected $connection;

    public function __construct($ci)
    {
        $this->ci = $ci;
    }

    public function connection($name = null)
    {
        if (null !== $name) {
            throw new \InvalidArgumentException("Named connections are not supported.");
        }

        if (null === $this->connection) {
            $this->connection = new CodeIgniterConnection($this->ci);
        }

        return $this->connection;
    }

    public function getDefaultConnection()
    {
        throw new \NotImplementedException;
    }

    public function setDefaultConnection($name)
    {
        throw new \NotImplementedException;
    }
}
