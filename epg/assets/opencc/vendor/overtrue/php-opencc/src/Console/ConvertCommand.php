<?php

namespace Overtrue\PHPOpenCC\Console;

use Overtrue\PHPOpenCC\OpenCC;
use Overtrue\PHPOpenCC\Strategy;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ConvertCommand extends Command
{
    protected static $defaultName = 'convert';

    protected static $defaultDescription = '中文简繁转换，支持词汇级别的转换、异体字转换和地区习惯用词转换（中国大陆、台湾、香港、日本新字体）。';

    protected function configure(): void
    {
        $this
            ->setDefinition(
                new InputDefinition([
                    new InputArgument('string', InputArgument::REQUIRED, '待转换的字符串'),
                    new InputArgument('strategy', InputArgument::OPTIONAL, '转换策略', Strategy::SIMPLIFIED_TO_TRADITIONAL),
                ])
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln(OpenCC::convert($input->getArgument('string'), $input->getArgument('strategy')));

        return Command::SUCCESS;
    }
}
