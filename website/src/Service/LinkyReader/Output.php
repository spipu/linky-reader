<?php

declare(strict_types=1);

namespace App\Service\LinkyReader;

class Output
{
    public function write(string $message): void
    {
        $memoryUsage = $this->formatMemory(memory_get_usage(true));
        $realMemoryUsage = $this->formatMemory(memory_get_peak_usage(true));

        echo sprintf(
            "[%s][%s][%s] %s\n",
            date('Y-m-d H:i:s'),
            $memoryUsage,
            $realMemoryUsage,
            $message
        );
    }

    private function formatMemory(int $value): string
    {
        return sprintf('%.2f', $value / 1024 / 1024) . ' Mo';
    }
}
