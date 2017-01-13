<?php
declare(strict_types=1);

namespace Owl;

use Psr\Log\LoggerInterface;

class Logger
{
    private static $logger;

    public static function setLogger(LoggerInterface $logger)
    {
        self::$logger = $logger;
    }

    public static function unsetLogger()
    {
        self::$logger = null;
    }

    public static function getLogger(): LoggerInterface
    {
        return self::$logger;
    }

    public static function log($level, string $message, array $context = [])
    {
        if ($logger = self::$logger) {
            $logger->log($level, $message, $context);
        }
    }

    public static function logException(\Throwable $exception, array $context)
    {
        if (!$logger = self::$logger) {
            return;
        }

        if ($previous = $exception->getPrevious()) {
            return self::logException($previous, $context);
        }

        $message = sprintf('%s(%d): %s', get_class($exception), $exception->getCode(), $exception->getMessage());
        $logger->error($message, $context);

        $traces = explode("\n", $exception->getTraceAsString());
        foreach ($traces as $trace) {
            $logger->error($trace);
        }
    }
}
