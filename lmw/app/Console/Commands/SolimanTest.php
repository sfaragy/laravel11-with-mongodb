<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Redis;

class SolimanTest extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'soliman:test';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->testRedis();
    }

    private function testRedis()
    {
        try {
            // Test connectivity
            $pingResponse = Redis::ping();
            $this->info('Redis server is running. Ping response: ' . $pingResponse);

            // Test storing and retrieving data
            Redis::set('test_key', 'test_value');
            $value = Redis::get('test_key');
            $this->info('Data stored and retrieved from Redis: ' . $value);

            // You can add more tests here...

            return 0;
        } catch (\Exception $e) {
            $this->error('Error testing Redis: ' . $e->getMessage());
            return 1;
        }
    }
}
