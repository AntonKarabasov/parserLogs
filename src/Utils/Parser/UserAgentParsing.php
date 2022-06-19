<?php

namespace App\Utils\Parser;

use App\Entity\UserAgent;
use App\Utils\Manager\UserAgentManager;
use WhichBrowser\Parser;

class UserAgentParsing
{

	/**
	 * @var UserAgentManager
	 */
	private UserAgentManager $userAgentManager;

	public function __construct(UserAgentManager $userAgentManager)
	{
		$this->userAgentManager = $userAgentManager;
	}

	public function parse(string $userAgent): UserAgent
	{
		$parsedUserAgent = new Parser($userAgent);
		$os = $parsedUserAgent->os->toString();
		$browser = $parsedUserAgent->browser->getName();
		$architecture = $this->parseArhitecture($userAgent);

		if ($os === '') {
			$os = 'unknown';
		}

		$userAgent = $this->userAgentManager->getRepository()->findOneBy([
			'os' => $os,
			'architecture' => $architecture,
			'browser' => $browser
		]);

		if ($userAgent) {
			return $userAgent;
		}

		$userAgent = new UserAgent();
		$userAgent->setOs($os);
		$userAgent->setArchitecture($architecture);
		$userAgent->setBrowser($browser);

		$this->userAgentManager->save($userAgent);

		return $userAgent;
	}

	public function parseArhitecture(string $userAgent): string
	{
		$pattern = '/WOW64/';
		preg_match($pattern, $userAgent, $matches);

		if (!empty($matches)) {
			return 'x64';
		}

		$pattern = '/x[4-8]{2}/';
		preg_match($pattern, $userAgent, $matches);

		if (!empty($matches)) {
			if ($matches[0] === 'x64') {
				return 'x64';
			}

			if ($matches[0] === 'x86') {
				return 'x86';
			}
		}

		return 'unknown';
	}
}