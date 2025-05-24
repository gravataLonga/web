<?php declare(strict_types=1);

use App\Http\Middleware\LogHttpRequestMiddleware;
use Odan\Session\Middleware\SessionStartMiddleware;
use Psr\Log\LoggerInterface;
use Slim\App;

return function (App $app) {

    /**
     * The routing middleware should be added earlier than the ErrorMiddleware
     * Otherwise exceptions thrown from it will not be handled by the middleware
     */
    $app->addRoutingMiddleware();

    /**
     * Add Error Middleware
     *
     * @param bool                  $displayErrorDetails -> Should be set to false in production
     * @param bool                  $logErrors -> Parameter is passed to the default ErrorHandler
     * @param bool                  $logErrorDetails -> Display error details in error log
     * @param LoggerInterface|null  $logger -> Optional PSR-3 Logger
     *
     * Note: This middleware should be added last. It will not handle any exceptions/errors
     * for middleware added after it.
     */
    $errorMiddleware = $app->addErrorMiddleware(true, true, true);

    $app->add(LogHttpRequestMiddleware::class);
    $app->add(SessionStartMiddleware::class);
};