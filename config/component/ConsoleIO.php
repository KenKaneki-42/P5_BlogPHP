<?php

namespace Core\component;

class ConsoleIO
{
    public function readLine()
    {
        return readline('Entrez quelque chose : ');
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
