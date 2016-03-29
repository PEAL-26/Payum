<?php
namespace Payum\Core\Tests\Model;

use Payum\Core\Model\GatewayConfig;
use Payum\Core\Model\GatewayConfigInterface;

class GatewayConfigTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function shouldExtendDetailsAwareInterface()
    {
        $rc = new \ReflectionClass(GatewayConfig::class);

        $this->assertTrue($rc->implementsInterface(GatewayConfigInterface::class));
    }

    /**
     * @test
     */
    public function couldBeConstructedWithoutAnyArguments()
    {
        new GatewayConfig();
    }

    /**
     * @test
     */
    public function shouldAllowGetPreviouslySetFactoryName()
    {
        $config = new GatewayConfig();

        $config->setFactoryName('theName');

        $this->assertEquals('theName', $config->getFactoryName());
    }

    /**
     * @test
     */
    public function shouldAllowGetPreviouslySetGatewayName()
    {
        $config = new GatewayConfig();

        $config->setGatewayName('theName');

        $this->assertEquals('theName', $config->getGatewayName());
    }

    /**
     * @test
     */
    public function shouldAllowGetDefaultConfigSetInConstructor()
    {
        $config = new GatewayConfig();

        $this->assertEquals(array(), $config->getConfig());
    }

    /**
     * @test
     */
    public function shouldAllowGetPreviouslySetConfig()
    {
        $config = new GatewayConfig();

        $config->setConfig(array('foo' => 'fooVal'));

        $this->assertEquals(array('foo' => 'fooVal'), $config->getConfig());
    }
}
