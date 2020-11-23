<?php

declare(strict_types=1);

namespace Owl;

use Psr\Log\LoggerInterface;

class Logger
{
    private static ?LoggerInterface $logger;

    public static function setLogger(LoggerInterface $logger): void
    {
        self::$logger = $logger;
    }

    public static function unsetLogger(): void
    {
        self::$logger = null;
    }

    public static function getLogger(): ?LoggerInterface
    {
        return self::$logger;
    }

    public static function log($level, $message, array $context = []): void
    {
        if ($logger = self::$logger) {
            $logger->log($level, $message, $context);
        }
    }

    public static function logException($exception, array $context): void
    {
        if (!$logger = self::$logger) {
            return;
        }

        if ($previous = $exception->getPrevious()) {
            self::logException($previous, $context);
            return;
        }

        $message = sprintf('%s(%d): %s', get_class($exception), $exception->getCode(), $exception->getMessage());
        $logger->error($message, $context);

        $traces = explode("\n", $exception->getTraceAsString());
        foreach ($traces as $trace) {
            $logger->error($trace);
        }
    }
}
