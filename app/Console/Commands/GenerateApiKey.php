<?php

namespace App\Console\Commands;

use App\Models\ApiKey;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class GenerateApiKey extends Command
{
    protected $signature = 'generate:apikey';
    protected $description = 'Generate a new API key';

    public function handle()
    {
        $apiKey = Str::random(32);

        ApiKey::create([
            'key' => $apiKey,
        ]);

        $this->info("API key generated: {$apiKey}");
    }
}