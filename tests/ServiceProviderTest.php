<?php

declare(strict_types=1);

namespace Rinvex\Categorizable\Test;

use ReflectionClass;
use PHPUnit\Framework\TestCase;
use Illuminate\Container\Container;
use Illuminate\Support\ServiceProvider;
use Rinvex\Categorizable\Providers\CategorizableServiceProvider;

class ServiceProviderTest extends TestCase
{
    /** Get the service provider class. */
    protected function getServiceProviderClass(): string
    {
        return CategorizableServiceProvider::class;
    }

    /** @test */
    public function it_is_a_service_provider()
    {
        $class = $this->getServiceProviderClass();

        $reflection = new ReflectionClass($class);

        $provider = new ReflectionClass(ServiceProvider::class);

        $msg = "Expected class '$class' to be a service provider.";

        $this->assertTrue($reflection->isSubclassOf($provider), $msg);
    }

    /** @test */
    public function it_has_provides_method()
    {
        $class = $this->getServiceProviderClass();
        $reflection = new ReflectionClass($class);

        $method = $reflection->getMethod('provides');
        $method->setAccessible(true);

        $msg = "Expected class '$class' to provide a valid list of services.";

        $this->assertInternalType('array', $method->invoke(new $class(new Container())), $msg);
    }
}
