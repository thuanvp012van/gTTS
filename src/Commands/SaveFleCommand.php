<?php

namespace Thuanvp012van\GTTS\Commands;

use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Thuanvp012van\GTTS\Language;
use Thuanvp012van\GTTS\GTTS;

#[AsCommand(
    'save',
    "Read <text> to mp3 format using Google Translate's Text-to-Speech API."
)]
class SaveFleCommand extends Command
{
    protected function configure(): void
    {
        $this->addOption('file', 'f', InputOption::VALUE_REQUIRED, 'File path');
        $this->addOption('language', 'l', InputOption::VALUE_REQUIRED, 'Language', Language::EN->getName());
        $this->addOption(
            'auto-detection',
            null,
            InputOption::VALUE_NEGATABLE,
            'Automatic language detection',
            false
        );
        $this->addOption('tld', null, InputOption::VALUE_REQUIRED, 'Top level domain', 'com');
        $this->addOption('slow', null, InputOption::VALUE_NONE, 'Slow reading speed');
        $this->addArgument('text', InputArgument::REQUIRED, 'Text to audio');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $gtts = new GTTS(
            $input->getArgument('text'),
            Language::getCaseByKey($input->getOption('language')),
            $input->getOption('slow'),
            $input->getOption('tld')
        );
        $gtts->autoDetection($input->getOption('auto-detection'));
        $file = $input->getOption('file');
        $file = is_dir(dirname($file)) ? $file : getcwd() . '/' . $file;
        $gtts->save($file);
        return Command::SUCCESS;
    }
}
