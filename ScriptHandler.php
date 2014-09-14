<?php

namespace DavidBarratt\DrupalStructure;

use Composer\Script\Event;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;

class ScriptHandler {

    public static function postUpdate(Event $event)
    {
        $structure = new Structure($event, new Filesystem(), new Finder());
        $structure->run();
    }

}
