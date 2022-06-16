<?php

namespace App\Command;

use App\Utils\Parser\LogParsing;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Stopwatch\Stopwatch;

class StartParsingCommand extends Command
{
    protected static $defaultName = 'app:start-parsing';
    protected static $defaultDescription = 'Parse logs';

	/**
	 * @var LogParsing
	 */
	private LogParsing $logParser;

	public function __construct(string $name = null, LogParsing $logParser)
	{
		parent::__construct($name);
		$this->logParser = $logParser;
	}

	protected function configure(): void
    {
        $this
	        ->addOption('filename', 'filename', InputArgument::REQUIRED, 'Filename')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

	    $stopwatch = new Stopwatch();
	    $stopwatch->start('start-parsing-command');

	    $filename = $input->getOption('filename');

	    $io->title('Parse logs');
	    $io->text([
		    'Please, enter filename'
	    ]);

        if ($filename) {
	        $filename = $io->ask('$filename');
        }

	    $lines = (file(__DIR__ . '/../../' . $filename,  FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES));

	    try {
		    $this->logParser->parse($lines);;
	    } catch (\RuntimeException $exception) {
		    $io->comment($exception->getMessage());

		    return Command::FAILURE;
	    }

        $io->success('Parsing done');

	    $event = $stopwatch->stop('start-parsing-command');
	    $stopwatchMessage = sprintf('Elapsed time: %.2f ms / Consumed memory: %.2f MB',
		    $event->getDuration(),
		    $event->getMemory() / 1000 / 1000
	    );
	    $io->comment($stopwatchMessage);

	    return Command::SUCCESS;
    }
}
