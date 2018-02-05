<?php

namespace RedirectionIO\Client\SymfonyBundle\Tests\EventListener;

use PHPUnit\Framework\TestCase;
use RedirectionIO\Client\Sdk\Client;
use RedirectionIO\Client\SymfonyBundle\EventListener\RequestResponseListener;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\Event\PostResponseEvent;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\PhpExecutableFinder;
use Symfony\Component\Process\Process;

/**
 * @covers \RedirectionIO\Client\SymfonyBundle\EventListener\RequestResponseListener
 */
class RequestResponseListenerTest extends TestCase
{
    private $listener;
    private $getResponseEvent;
    private $postResponseEvent;

    public static function setUpBeforeClass()
    {
        static::startAgent();
    }

    protected function setUp()
    {
        $client = new Client(['agent' => ['host' => 'localhost', 'port' => 3100]]);
        $this->listener = new RequestResponseListener($client);
    }

    public function testWhenRedirectRuleExists()
    {
        $this->createInitialState(['path' => 'foo']);

        $this->listener->onKernelRequest($this->getResponseEvent);

        $this->assertInstanceOf(RedirectResponse::class, $this->getResponseEvent->getResponse());
        $this->assertSame(301, $this->getResponseEvent->getResponse()->getStatusCode());
        $this->assertSame('http://host1.com/bar', $this->getResponseEvent->getResponse()->getTargetUrl());

        $this->assertSame($this->listener->onKernelTerminate($this->postResponseEvent), true);
    }

    public function testWhenRedirectRuleNotExists()
    {
        $this->createInitialState(['path' => 'hello']);

        $this->listener->onKernelRequest($this->getResponseEvent);
        $this->assertNull($this->getResponseEvent->getResponse());

        $this->assertSame($this->listener->onKernelTerminate($this->postResponseEvent), true);
    }

    public function testWhenIsSubRequest()
    {
        $this->createInitialState(['type' => 2]);

        $this->assertSame($this->listener->onKernelRequest($this->getResponseEvent), false);
    }

    public function testWhenAgentIsDown()
    {
        $client = new Client(['agent' => ['host' => 'localhost', 'port' => 3101]]);
        $this->listener = new RequestResponseListener($client);

        $this->createInitialState(['path' => 'foo']);

        $this->assertSame($this->listener->onKernelRequest($this->getResponseEvent), false);
        $this->assertSame($this->listener->onKernelTerminate($this->postResponseEvent), false);
    }

    private static function startAgent($port = 3100)
    {
        $finder = new PhpExecutableFinder();
        if (false === $binary = $finder->find()) {
            throw new \RuntimeException('Unable to find PHP binary to run a fake agent.');
        }

        // find fake_agent location
        $parentFolder = substr(__DIR__, -27, -20);
        $fakeAgent = ('package' === $parentFolder) ?
            __DIR__ . '/../../../../sdk/src/Resources/fake_agent.php' :
            './redirectionio-sdk/src/Resources/fake_agent.php';

        $agent = new Process([$binary, $fakeAgent]);
        $agent
            ->inheritEnvironmentVariables(true)
            ->setEnv(['RIO_PORT' => $port])
            ->start()
        ;

        static::waitUntilProcReady($agent);

        if ($agent->isTerminated() && !$agent->isSuccessful()) {
            throw new ProcessFailedException($agent);
        }

        register_shutdown_function(function () use ($agent) {
            $agent->stop();
        });

        return $agent;
    }

    private function createInitialState($requestOptions = [], $responseOptions = [])
    {
        $host = array_key_exists('host', $requestOptions) ? $requestOptions['host'] : 'host1.com';
        $path = array_key_exists('path', $requestOptions) ? $requestOptions['path'] : '';
        $userAgent = array_key_exists('user_agent', $requestOptions) ? $requestOptions['user_agent'] : 'redirection-io-client/0.0.1';
        $type = array_key_exists('type', $requestOptions) ? $requestOptions['type'] : 1;
        // 1 = HttpKernelInterface::MASTERREQUEST
        // 2 = HttpKernelInterface::SUBREQUEST

        $request = new Request([], [], [], [], [], ['HTTP_HOST' => $host, 'REQUEST_URI' => $path]);
        $request->headers->set('User-Agent', $userAgent);
        $requestType = $type;

        $response = new Response();

        $kernel = $this->getMockBuilder('Symfony\Component\HttpKernel\HttpKernelInterface')->getMock();
        $this->postResponseEvent = new PostResponseEvent($kernel, $request, $response);
        $this->getResponseEvent = new GetResponseEvent($kernel, $request, $requestType);
    }

    private static function waitUntilProcReady(Process $proc)
    {
        while (true) {
            usleep(50000);
            foreach ($proc as $type => $data) {
                if ($proc::OUT === $type || $proc::ERR === $type) {
                    break 2;
                }
            }
        }
    }
}
