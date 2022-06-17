<?php

namespace Dnd;

use React\EventLoop\Loop;
use Sohris\Core\Component\AbstractComponent;
use Sohris\Core\Logger;

class Servers extends AbstractComponent
{

    private $module_name = "Aaa";


    public function __construct()
    {
        $this->loop = Loop::get();
        Loop::addPeriodicTimer(1, fn() => var_dump(123));
        $this->logger = new Logger('Aaa');
    }

    public function install()
    {

    }

    public function start()
    {
        $running = new Running;
        $running->run();
    }
}
