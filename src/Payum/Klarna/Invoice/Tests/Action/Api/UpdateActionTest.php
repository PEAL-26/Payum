<?php

namespace Payum\Klarna\Invoice\Tests\Action\Api;

use Klarna;
use KlarnaException;
use Payum\Core\Exception\RequestNotSupportedException;
use Payum\Core\Exception\UnsupportedApiException;
use Payum\Core\GatewayAwareInterface;
use Payum\Core\GatewayInterface;
use Payum\Core\Tests\GenericApiAwareActionTest;
use Payum\Klarna\Invoice\Action\Api\BaseApiAwareAction;
use Payum\Klarna\Invoice\Action\Api\UpdateAction;
use Payum\Klarna\Invoice\Config;
use Payum\Klarna\Invoice\Request\Api\PopulateKlarnaFromDetails;
use Payum\Klarna\Invoice\Request\Api\Update;
use PHPUnit\Framework\MockObject\MockObject;
use PhpXmlRpc\Client;
use ReflectionClass;
use ReflectionProperty;
use stdClass;

class UpdateActionTest extends GenericApiAwareActionTest
{
    public function testShouldBeSubClassOfBaseApiAwareAction()
    {
        $rc = new ReflectionClass(UpdateAction::class);

        $this->assertTrue($rc->isSubclassOf(BaseApiAwareAction::class));
    }

    public function testShouldImplementsGatewayAwareInterface()
    {
        $rc = new ReflectionClass(UpdateAction::class);

        $this->assertTrue($rc->implementsInterface(GatewayAwareInterface::class));
    }

    public function testShouldAllowSetGateway()
    {
        $this->assertInstanceOf(GatewayAwareInterface::class, new UpdateAction($this->createKlarnaMock()));
    }

    public function testThrowApiNotSupportedIfNotConfigGivenAsApi()
    {
        $this->expectException(UnsupportedApiException::class);
        $this->expectExceptionMessage('Not supported api given. It must be an instance of Payum\Klarna\Invoice\Config');
        $action = new UpdateAction($this->createKlarnaMock());

        $action->setApi(new stdClass());
    }

    public function testShouldSupportUpdateWithArrayAsModel()
    {
        $action = new UpdateAction();

        $this->assertTrue($action->supports(new Update([])));
    }

    public function testShouldNotSupportAnythingNotUpdate()
    {
        $action = new UpdateAction();

        $this->assertFalse($action->supports(new stdClass()));
    }

    public function testShouldNotSupportUpdateWithNotArrayAccessModel()
    {
        $action = new UpdateAction();

        $this->assertFalse($action->supports(new Update(new stdClass())));
    }

    public function testThrowIfNotSupportedRequestGivenAsArgumentOnExecute()
    {
        $this->expectException(RequestNotSupportedException::class);
        $action = new UpdateAction();

        $action->execute(new stdClass());
    }

    public function testShouldCallKlarnaUpdate()
    {
        $details = [
            'rno' => 'theRno',
        ];

        $gatewayMock = $this->createGatewayMock();
        $gatewayMock
            ->expects($this->once())
            ->method('execute')
            ->with($this->isInstanceOf(PopulateKlarnaFromDetails::class))
        ;

        $klarnaMock = $this->createKlarnaMock();
        $klarnaMock
            ->expects($this->once())
            ->method('update')
            ->with($details['rno'])
            ->willReturn(true)
        ;

        $action = new UpdateAction($klarnaMock);
        $action->setApi(new Config());
        $action->setGateway($gatewayMock);

        $action->execute($request = new Update($details));

        $model = $request->getModel();
        $this->assertSame('theRno', $model['rno']);
        $this->assertTrue($model['updated']);
    }

    public function testShouldCatchKlarnaExceptionAndSetErrorInfoToDetails()
    {
        $details = [
            'rno' => 'theRno',
        ];

        $gatewayMock = $this->createGatewayMock();
        $gatewayMock
            ->expects($this->once())
            ->method('execute')
            ->with($this->isInstanceOf(PopulateKlarnaFromDetails::class))
        ;

        $klarnaMock = $this->createKlarnaMock();
        $klarnaMock
            ->expects($this->once())
            ->method('update')
            ->with($details['rno'])
            ->willThrowException(new KlarnaException('theMessage', 123))
        ;

        $action = new UpdateAction($klarnaMock);
        $action->setApi(new Config());
        $action->setGateway($gatewayMock);

        $action->execute($request = new Update($details));

        $model = $request->getModel();
        $this->assertSame(123, $model['error_code']);
        $this->assertSame('theMessage', $model['error_message']);
    }

    protected function getActionClass(): string
    {
        return UpdateAction::class;
    }

    protected function getApiClass()
    {
        return new Config();
    }

    /**
     * @return MockObject|Klarna
     */
    protected function createKlarnaMock()
    {
        $klarnaMock = $this->createMock(Klarna::class, ['config', 'update']);

        $rp = new ReflectionProperty($klarnaMock, 'xmlrpc');
        $rp->setAccessible(true);
        $rp->setValue($klarnaMock, $this->createMock(class_exists('xmlrpc_client') ? 'xmlrpc_client' : Client::class));
        $rp->setAccessible(false);

        return $klarnaMock;
    }

    /**
     * @return MockObject|GatewayInterface
     */
    protected function createGatewayMock()
    {
        return $this->createMock(GatewayInterface::class);
    }
}
