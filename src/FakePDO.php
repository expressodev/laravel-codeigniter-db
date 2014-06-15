<?php

namespace Illuminate\CodeIgniter;

/**
 * Fake PDO for servers with PDO disabled
 */
interface FakePDO
{
    const FETCH_ASSOC = 2;
    const FETCH_OBJ = 5;
}
