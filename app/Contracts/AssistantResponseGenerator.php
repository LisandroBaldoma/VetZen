<?php

namespace App\Contracts;

interface AssistantResponseGenerator
{
    public function generate(string $instructions, string $prompt): string;
}
