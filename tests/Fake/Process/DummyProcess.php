<?php

/*
 * This file is part of the Moodle Plugin CI package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * Copyright (c) 2018 Blackboard Inc. (http://www.blackboard.com)
 * License http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace MoodlePluginCI\Tests\Fake\Process;

use Symfony\Component\Process\Process;

class DummyProcess extends Process
{
    private $realcommandline;

    public function __construct($commandline)
    {
        $this->realcommandline = $commandline;
    }

    public function getCommandLine()
    {
        return $this->realcommandline;
    }

    public function getOutput()
    {
        return '';
    }

    public function isSuccessful()
    {
        return true;
    }
}
