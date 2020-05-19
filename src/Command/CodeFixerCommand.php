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

namespace MoodlePluginCI\Command;

use MoodlePluginCI\Bridge\CodeSnifferCLI;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Run PHP Code Beautifier and Fixer on a plugin.
 */
class CodeFixerCommand extends CodeCheckerCommand
{
    protected function configure()
    {
        AbstractPluginCommand::configure();

        $this->setName('phpcbf')
            ->setDescription('Run Code Beautifier and Fixer on a plugin')
            ->addOption('standard', 's', InputOption::VALUE_REQUIRED, 'The name or path of the coding standard to use', 'moodle');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->outputHeading($output, 'Code Beautifier and Fixer on %s');

        $files = $this->plugin->getFiles($this->finder);
        if (count($files) === 0) {
            return $this->outputSkip($output, 'No files found to process.');
        }
        if (!defined('PHP_CODESNIFFER_CBF')) { // Can be defined in tests.
            define('PHP_CODESNIFFER_CBF', true);
        }

        $cli = new CodeSnifferCLI([
            'reports'       => ['cbf' => null],
            'colors'        => $output->isDecorated(),
            'encoding'      => 'utf-8',
            'files'         => $files,
            'reportWidth'   => 120,
            'phpcbf-suffix' => '',
        ]);

        $sniffer = new \PHP_CodeSniffer();
        $sniffer->setCli($cli);
        $sniffer->process($files, $this->standard);
        $sniffer->reporting->printReport('cbf', false, $sniffer->cli->getCommandLineValues(), null, 120);

        return 0;
    }
}
