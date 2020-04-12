<?php
declare(strict_types = 1);

namespace App\Service;

class Output
{
    /**
     * @param string $message
     */
    public function write(string $message): void
    {
        echo $message . "\n";
    }
}