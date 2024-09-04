<?php

namespace PHPNomad\Facade\Tests\Unit\Abstracts;

use Mockery;
use PHPNomad\Di\Container;
use PHPNomad\Di\Exceptions\DiException;
use PHPNomad\Facade\Abstracts\Facade;
use PHPNomad\Facade\Tests\TestCase;
use PHPNomad\Logger\Interfaces\LoggerStrategy;
use PHPNomad\Tests\Traits\WithInaccessibleMethods;
use ReflectionException;

class FacadeTest extends TestCase
{
    use WithInaccessibleMethods;

    /**
     * @var Container&Mockery\MockInterface
     */
    protected $container;

    /**
     * @var Facade<object>&Mockery\MockInterface
     */
    protected $instance;

    public function setUp(): void
    {
        parent::setUp();
        $this->container = Mockery::mock(Container::class);

        $this->instance = Mockery::mock(Facade::class)->makePartial()->shouldAllowMockingProtectedMethods();
        $this->instance->setContainer($this->container);
        $this->instance->allows('abstractInstance')->andReturn(Facade::class);

    }

    /**
     * @covers \PHPNomad\Facade\Abstracts\Facade::getContainedInstance()
     * @return void
     * @throws ReflectionException
     */
    public function testCanGetContainedInstance()
    {
        $this->container->expects('get')->with(Facade::class)->andReturn($this->instance);

        $actual = $this->callInaccessibleMethod($this->instance, 'getContainedInstance');

        $this->assertSame($this->instance, $actual);
    }

    /**
     * @covers \PHPNomad\Facade\Abstracts\Facade::getContainedInstance()
     * @return void
     * @throws ReflectionException
     */
    public function testGetContainedInstanceLogsExceptions(): void
    {
        $logger = Mockery::mock(LoggerStrategy::class)->makePartial();
        $logger->expects('critical')->with('test', ['container' => $this->container, 'abstract' => Facade::class]);

        $this->container->allows('get')->with(LoggerStrategy::class)->andReturn($logger);
        $this->container->allows('get')->with(Facade::class)->andThrow(new DiException('test'));

        $this->expectException(DiException::class);
        $this->expectExceptionMessage('test');
        $this->callInaccessibleMethod($this->instance, 'getContainedInstance');
    }
}