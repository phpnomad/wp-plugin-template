<?php

namespace PHPNomad\Core\Tests\Unit\Facades;

use Generator;
use Mockery;
use Mockery\MockInterface;
use PHPNomad\Core\Facades\Logger;
use PHPNomad\Core\Tests\TestCase;
use PHPNomad\Logger\Interfaces\LoggerStrategy;
use PHPNomad\Tests\Traits\WithInaccessibleMethods;
use ReflectionException;

class LoggerTest extends TestCase
{
    use WithInaccessibleMethods;

    /**
     * @var Logger&MockInterface
     */
    protected $facade;

    /**
     * @var LoggerStrategy&MockInterface
     */
    protected $containedMock;

    /**
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->facade = Mockery::mock(Logger::class)->makePartial()->shouldAllowMockingProtectedMethods();
        $this->containedMock = Mockery::mock(LoggerStrategy::class);

        $this->facade->allows('instance->getContainedInstance')
            ->andReturn($this->containedMock);
    }

    /**
     * @covers       \use PHPNomad\Core\Facades\Logger::load
     * @dataProvider providerForMethods
     */
    public function testCanProvideMethods(string $method): void
    {
        $message = 'message';
        $context = ['foo'];

        $this->containedMock->expects($method)
            ->once()
            ->with($message, $context);

        $this->facade::{$method}($message, $context);
    }

    public function providerForMethods(): Generator
    {
        yield ['emergency'];
        yield ['alert'];
        yield ['critical'];
        yield ['error'];
        yield ['warning'];
        yield ['notice'];
        yield ['info'];
        yield ['debug'];
    }

    /**
     * @covers \PHPNomad\Core\Facades\Logger::abstractInstance
     * @throws ReflectionException
     */
    public function testAbstractInstanceMatchesExpected(): void
    {
        $actual = $this->callInaccessibleMethod(new Logger(), 'abstractInstance');

        $this->assertEquals(LoggerStrategy::class, $actual);
    }
}