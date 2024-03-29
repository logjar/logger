<?php

declare(strict_types=1);

use Logjar\Logger\Handler\SocketHandler;
use Logjar\Logger\Logger;
use Logjar\Logger\Record;
use PHPUnit\Framework\TestCase;

final class LoggerTest extends TestCase
{
	public function testSendLogs(): void
	{
		$this->expectNotToPerformAssertions();

		$handler = new SocketHandler('http://localhost:8080', 1, '1234');
		$logger = new Logger();
		$logger->pushHandler($handler);
		$logger->alert('Line5');
		// sleep(1);
		$logger->debug('Line6');
	}

	public function testInvalidHost(): void
	{
		$this->expectNotToPerformAssertions();

		$handler = new SocketHandler('http://abc.def:80', 1, '1234');
		$record = new Record('debug', 'test', [], new DateTimeImmutable('now'));
		$handler->handle($record);
	}

	public function testTimezone(): void
	{
		$handler = new SocketHandler('', 1, '');
		$logger = new Logger();
		$logger->pushHandler($handler);

		$timezone = $logger->getTimezone();
		$this->assertEquals('UTC', $timezone->getName());

		$logger->setTimezone(new DateTimeZone('Europe/Berlin'));
		$timezone = $logger->getTimezone();
		$this->assertEquals('Europe/Berlin', $timezone->getName());
	}
}
