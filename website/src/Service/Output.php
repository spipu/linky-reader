<?php

declare(strict_types=1);

namespace App\Service;

class Output
{
    public function write(string $message): void
    {
        echo '[' . date('Y-m-d H:i:s') . '] ';
        echo $message;
        echo "\n";
    }
}
