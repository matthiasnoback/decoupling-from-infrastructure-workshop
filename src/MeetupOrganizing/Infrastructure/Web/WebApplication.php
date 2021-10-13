<?php
declare(strict_types=1);

namespace MeetupOrganizing\Infrastructure\Web;

use RuntimeException;
use Throwable;

final class WebApplication
{
    private array $server;
    private object $controllers;

    public function __construct(array $server, object $controllers)
    {
        $this->server = $server;
        $this->controllers = $controllers;
    }

    public static function createFromGlobalsWithControllers(object $controllers): self
    {
        return new self($_SERVER, $controllers);
    }

    public function run(): void
    {
        $callable = $this->resolveController();

        $callable();
    }

    /**
     * When the request URI is "/someRoute/", the resolver looks for a method "someRouteController" on the provided
     * controllers object.
     * If it can't be found, a generic 404 controller will be returned.
     */
    private function resolveController(): callable
    {
        $action = trim($this->determinePathInfo(), '/');
        $controllerMethod = [$this->controllers, ($action ?: 'index') . 'Controller'];

        if (!is_callable($controllerMethod)) {
            return $this->create404Controller();
        }

        return function () use ($controllerMethod) {
            try {
                ob_start();

                call_user_func($controllerMethod);

                ob_end_flush();
            } catch (Throwable $throwable) {
                ob_end_clean();

                http_response_code(500);

                throw $throwable;
            }
        };
    }

    private function create404Controller(): callable
    {
        return function () {
            error_log('ControllerResolver: No matching controller method, create 404 response');
            if (PHP_SAPI !== 'cli') {
                header('Content-Type: text/plain', true, 404);
            }
            echo "Page not found\n";

            $controllerMethods = array_filter(
                get_class_methods($this->controllers),
                function (string $methodName) {
                    return substr($methodName, -10) === 'Controller';
                }
            );

            $uris = array_map(
                function (string $methodName) {
                    return '/' . substr($methodName, 0, -10);
                },
                $controllerMethods
            );

            if (!empty($uris)) {
                echo "\nYou could try:\n";
                foreach ($uris as $uri) {
                    echo "- $uri\n";
                }
            }
        };
    }

    private function determinePathInfo(): string
    {
        if (isset($this->server['PATH_INFO'])) {
            return $this->server['PATH_INFO'];
        }

        // works for PHP-FPM
        if (isset($this->server['REQUEST_URI'])) {

            $requestUri = $this->server['REQUEST_URI'];
            if (empty($requestUri)) {
                return '/';
            }

            if ($pos = strpos($requestUri, '?')) {
                // return the request URI without query parameters
                return substr($requestUri, 0, $pos);
            }

            // the request URI doesn't contain any query parameters, return as is
            return $requestUri;
        }

        throw new RuntimeException('Could not determine path info (based on either PATH_INFO or REQUEST_URI)');
    }
}
