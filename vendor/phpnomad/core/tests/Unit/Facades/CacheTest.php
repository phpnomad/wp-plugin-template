<?php

namespace PHPNomad\Core\Tests\Unit\Facades;

use Mockery;
use Mockery\MockInterface;
use PHPNomad\Cache\Interfaces\CacheStrategy;
use PHPNomad\Core\Facades\Cache;
use PHPNomad\Core\Tests\TestCase;
use PHPNomad\Tests\Traits\WithInaccessibleMethods;
use ReflectionException;

class CacheTest extends TestCase
{
    use WithInaccessibleMethods;

    /**
     * @var Cache&MockInterface
     */
    protected $facade;

    /**
     * @var CacheStrategy&MockInterface
     */
    protected $containedMock;

    public function setUp(): void
    {
        parent::setUp();
        $this->facade = Mockery::mock(Cache::class)->makePartial()->shouldAllowMockingProtectedMethods();
        $this->containedMock = Mockery::mock(CacheStrategy::class);

        $this->facade->allows('instance->getContainedInstance')
            ->andReturn($this->containedMock);
    }

    /**
     * @covers \PHPNomad\Core\Facades\Event::load
     */
    public function testCanLoad(): void
    {
        $key = 'foo-key';
        $setter = function () {
        };
        $ttl = 123;

        $this->containedMock->expects('load')
            ->once()
            ->with($key, $setter, $ttl);

        $this->facade::load($key, $setter, $ttl);
    }

    /**
     * @covers \PHPNomad\Core\Facades\Event::get
     */
    public function testCanGet(): void
    {
        $key = 'foo-key';

        $this->containedMock->expects('get')
            ->once()
            ->with($key);

        $this->facade::get($key);
    }

    /**
     * @covers \PHPNomad\Core\Facades\Event::set
     */
    public function testCanSet(): void
    {
        $key = 'foo-key';
        $value = 'foo-value';
        $ttl = 123;

        $this->containedMock->expects('set')
            ->once()
            ->with($key, $value, $ttl);

        $this->facade::set($key, $value, $ttl);
    }

    /**
     * @covers \PHPNomad\Core\Facades\Event::abstractInstance
     * @throws ReflectionException
     */
    public function testAbstractInstanceMatchesExpected(): void
    {
        $actual = $this->callInaccessibleMethod(new Cache(), 'abstractInstance');

        $this->assertEquals(CacheStrategy::class, $actual);
    }
}
