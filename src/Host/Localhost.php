<?php

namespace Nanbando\Host;

class Localhost extends Host
{
    public function __construct()
    {
        parent::__construct('localhost', 'localhost');
    }
}
