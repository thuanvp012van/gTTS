<?php

namespace Thuanvp012van\GTTS\Commands;

use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Thuanvp012van\GTTS\Language;

#[AsCommand('languages', 'Get languages.')]
class ListLanguagesCommand extends Command
{
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        foreach (Language::cases() as $lang) {
            $key = $lang->getName();
            $output->writeln("  $key - $lang->value");
        }
        return Command::SUCCESS;
    }
}