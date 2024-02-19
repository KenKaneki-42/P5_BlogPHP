<?php

namespace Core\Component;

class ConsoleIO
{
    public function readLine(string $keyword)
    {
        $instruction = sprintf('write the %s', $keyword);
        return readline($instruction);
    }

    public function writeLine($message)
    {
        echo $message . PHP_EOL;
    }
}
