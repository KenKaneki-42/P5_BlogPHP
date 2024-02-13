<?php

namespace Core\component;

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

// Exemple
// $consoleIO = new ConsoleIO();
// $input = $consoleIO->readLine();
// $consoleIO->writeLine('Vous avez saisi : ' . $input);
