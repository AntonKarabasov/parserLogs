<?php

namespace App\Utils\Parser;

use App\Entity\Log;
use App\Utils\Manager\LogManager;
use Exception;
use Kassner\LogParser\LogParser;

class LogParsing
{
	/**
	 * @var LogParser
	 */
	private LogParser $logParser;

	/**
	 * @var UserAgentParsing
	 */
	private UserAgentParsing $userAgentParsing;

	/**
	 * @var LogManager
	 */
	private LogManager $logManager;

	public function __construct(LogManager $logManager, UserAgentParsing $userAgentParsing)
	{
		$this->logManager = $logManager;
		$this->userAgentParsing = $userAgentParsing;
		$this->logParser = new LogParser();
		$this->logParser->setFormat('%h %l %u %t "%r" %>s %O "%{Referer}i" \"%{User-Agent}i"');
	}

	public function parse(array $lines)
	{
		foreach ($lines as $line) {
			try {
				$entry = $this->logParser->parse($line);
			} catch (Exception $FormatException) {
				continue;
			}

			$userAgent = $this->userAgentParsing->parse($entry->HeaderUserAgent);

			$log = new Log();
			$log->setIp($entry->host);
			$log->setDatetime(date_create($entry->time));
			$log->setUrl($entry->HeaderReferer);
			$log->setUserAgent($userAgent);

			$this->logManager->getEntityManager()->persist($log);
		}
		$this->logManager->getEntityManager()->flush();

		echo 'Done!';
	}
}