<?php

namespace Illuminate\CodeIgniter;

use PDO;
use Illuminate\Database\Connection;

class CodeIgniterConnection extends Connection
{
    public $ci;

    /**
     * Create a new database connection instance.
     *
     * @param  object $ci
     * @return void
     */
    public function __construct($ci)
    {
        $this->ci = $ci;
        $this->tablePrefix = $this->ci->db->dbprefix;
        $this->useDefaultQueryGrammar();
        $this->useDefaultPostProcessor();
    }

    /**
     * Get the default query grammar instance.
     *
     * @return Illuminate\Database\Query\Grammars\Grammars\Grammar
     */
    protected function getDefaultQueryGrammar()
    {
        $driver = $this->ci->db->dbdriver;
        switch ($driver) {
            case 'mysql':
                return $this->withTablePrefix(new \Illuminate\Database\Query\Grammars\MySqlGrammar);
        }

        throw new \InvalidArgumentException("Unknown CI database driver '$driver'");
    }

    /**
     * Get the default schema grammar instance.
     *
     * @return Illuminate\Database\Schema\Grammars\Grammar
     */
    protected function getDefaultSchemaGrammar()
    {
        $driver = $this->ci->db->dbdriver;
        switch ($driver) {
            case 'mysql':
                return $this->withTablePrefix(new \Illuminate\Database\Schema\Grammars\MySqlGrammar);
        }

        throw new \InvalidArgumentException("Unsupported CodeIgniter database driver '$driver'");
    }

    /**
     * Get the default post processor instance.
     *
     * @return Illuminate\Database\Query\Processors\Processor
     */
    protected function getDefaultPostProcessor()
    {
        return new CodeIgniterProcessor;
    }

    /**
     * Get the currently used PDO connection.
     *
     * @return PDO
     */
    public function getPdo()
    {
        throw new \BadMethodCallException('PDO is not supported by CodeIgniter database driver');
    }

    /**
     * Run a select statement against the database.
     *
     * @param  string $query
     * @param  array  $bindings
     * @return array
     */
    public function select($query, $bindings = array())
    {
        $self = $this;

        return $this->run($query, $bindings, function($me, $query, $bindings) use ($self) {
            if ($me->pretending()) return array();

            // pass query to CodeIgniter database layer
            $bindings = $me->prepareBindings($bindings);

            return $self->fetchResult($self->ci->db->query($query, $bindings));
        });
    }

    /**
     * Fetch a CodeIgniter result set as an array or object, emulating current PDO fetch mode
     *
     * @param  object $result
     * @return mixed
     */
    public function fetchResult($result)
    {
        $fetchMode = $this->getFetchMode();
        switch ($fetchMode) {
            case PDO::FETCH_ASSOC:
                return $result->result_array();
            case PDO::FETCH_OBJ:
                return $result->result();
        }

        throw new \BadMethodCallException("Unsupported fetch mode '$fetchMode'.");
    }

    /**
     * Execute an SQL statement and return the boolean result.
     *
     * @param  string $query
     * @param  array  $bindings
     * @return bool
     */
    public function statement($query, $bindings = array())
    {
        $self = $this;

        return $this->run($query, $bindings, function($me, $query, $bindings) use ($self) {
            if ($me->pretending()) return true;

            // pass query to CodeIgniter database layer
            $bindings = $me->prepareBindings($bindings);

            return (bool) $self->ci->db->query($query, $bindings);
        });
    }

    /**
     * Run an SQL statement and get the number of rows affected.
     *
     * @param  string $query
     * @param  array  $bindings
     * @return int
     */
    public function affectingStatement($query, $bindings = array())
    {
        $self = $this;

        return $this->run($query, $bindings, function($me, $query, $bindings) use ($self) {
            if ($me->pretending()) return 0;

            // pass query to CodeIgniter database layer
            $bindings = $me->prepareBindings($bindings);
            $self->ci->db->query($query, $bindings);

            // return number of rows affected
            return $self->ci->db->affected_rows();
        });
    }

    /**
     * Get the last insert id from
     */
    public function lastInsertId()
    {
        return $this->ci->db->insert_id();
    }
}
