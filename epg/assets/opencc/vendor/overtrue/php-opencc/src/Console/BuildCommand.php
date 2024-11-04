<?php

namespace Overtrue\PHPOpenCC\Console;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\Process;

class BuildCommand extends Command
{
    protected static $defaultName = 'build';

    protected static $defaultDescription = 'Build OpenCC data files.';

    const DICTIONARY_DIR = __DIR__.'/../../data/dictionary';

    const PARSED_DIR = __DIR__.'/../../data/parsed';

    const FILES = [
        'HKVariants',
        'HKVariantsRevPhrases',
        'JPShinjitaiCharacters',
        'JPShinjitaiPhrases',
        'JPVariants',
        'STCharacters',
        'STPhrases',
        'TSCharacters',
        'TSPhrases',
        'TWPhrasesIT',
        'TWPhrasesName',
        'TWPhrasesOther',
        'TWVariants',
        'TWVariantsRevPhrases',
    ];

    const MERGE_OUTPUT_MAP = [
        'TWPhrases' => ['TWPhrasesIT', 'TWPhrasesName', 'TWPhrasesOther'],
        'TWVariantsRev' => ['TWVariants'],
        'TWPhrasesRev' => ['TWPhrasesIT', 'TWPhrasesName', 'TWPhrasesOther'],
        'HKVariantsRev' => ['HKVariants'],
        'JPVariantsRev' => ['JPVariants'],
    ];

    const REVERSED_FILES = [
        'TWVariantsRev',
        'TWPhrasesRev',
        'HKVariantsRev',
        'JPVariantsRev',
    ];

    protected function configure(): void
    {
        $this
            ->setDefinition(
                new InputDefinition([
                    new InputOption('force', 'f', InputOption::VALUE_OPTIONAL),
                ])
            );
    }

    /**
     * @throws \Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        if (! file_exists(self::DICTIONARY_DIR)) {
            mkdir(self::DICTIONARY_DIR, 0755, true);
        }

        if (! file_exists(self::PARSED_DIR)) {
            mkdir(self::PARSED_DIR, 0755, true);
        }

        $file = self::DICTIONARY_DIR.'/STCharacters.txt';

        if (file_exists($file) && filemtime($file) > time() - 3600 * 24 && ! $input->hasOption('force')) {
            $output->writeln('Data files are up to date.');

            return Command::SUCCESS;
        }

        $this->download($output);
        $this->extract($output);
        $this->copy($output);
        $this->parse($output);

        return Command::SUCCESS;
    }

    /**
     * @throws \Exception
     */
    public function download(OutputInterface $output): void
    {
        $output->writeln('Downloading data files...');

        $zip = 'https://github.com/BYVoid/OpenCC/archive/refs/heads/master.zip';
        try {
            $process = Process::fromShellCommandline('curl -L -o /tmp/opencc.zip '.$zip);
            $process->setTty(Process::isTtySupported());
            $process->run();
        } catch (\Exception $e) {
            $output->writeln('Download failed.');
            throw $e;
        }

        $output->writeln('Done.');
    }

    public function copy(OutputInterface $output): void
    {
        $output->write('Copying data files...');
        $process = Process::fromShellCommandline('cp -rf /tmp/opencc/OpenCC-master/data/dictionary/* '.self::DICTIONARY_DIR);
        $process->setTty(Process::isTtySupported());
        $process->run();
        $output->writeln('Done.');
    }

    public function extract(OutputInterface $output): void
    {
        $output->write('Extracting data files...');
        $process = Process::fromShellCommandline('unzip -o /tmp/opencc.zip -d /tmp/opencc');
        $process->run();
        $output->writeln('Done.');
    }

    public function parse(OutputInterface $output): void
    {
        $output->writeln('Parsing dictionary files...');

        $files = array_merge(self::FILES, array_keys(self::MERGE_OUTPUT_MAP));

        foreach ($files as $file) {
            $output->writeln('Parsing '.$file.'...');
            $txt = sprintf('%s/%s.txt', self::DICTIONARY_DIR, $file);
            if (file_exists($txt)) {
                $lines = file($txt, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
            } else {
                // merge files
                $content = '';
                foreach (self::MERGE_OUTPUT_MAP[$file] as $f) {
                    $content .= file_get_contents(sprintf('%s/%s.txt', self::DICTIONARY_DIR, $f));
                }
                $lines = array_filter(explode("\n", $content));
            }

            $needReverse = in_array($file, self::REVERSED_FILES, true);

            $words = [];
            foreach ($lines as $line) {
                [$from, $to] = explode("\t", $line);
                $to = preg_split('/\s+/', $to, -1, PREG_SPLIT_NO_EMPTY)[0] ?? null;

                if (! $to) {
                    ! $to && $output->writeln('Skip '.$line);

                    continue;
                }

                if ($needReverse) {
                    [$from, $to] = [$to, $from];
                }

                // 会出现重复的词条，以最后一个为准
                $words[$from] = $to;
            }

            $content = sprintf('<?php return %s;', var_export($words, true));

            $target = sprintf('%s/%s.php', self::PARSED_DIR, $file);

            file_put_contents($target, $content);
        }

        $output->writeln('Done.');
    }
}
