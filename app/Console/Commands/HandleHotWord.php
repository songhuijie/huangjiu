<?php

namespace App\Console\Commands;

use App\Libraries\Lib_redis_key;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class HandleHotWord extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'handle:word {word}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        //

        $word = $this->argument('word');
        try{
            $hot_words = \Redis::get(Lib_redis_key::HOT_WORDS);
            if($hot_words){

                $hot_words = json_decode($hot_words,true);
                if(isset($hot_words[$word])){
                    $hot_words[$word] += 1;
                }else{
                    $hot_words[$word] = 1;
                }
            }else{
                $hot_words[$word] = 1;
            }
            \Redis::set(Lib_redis_key::HOT_WORDS,json_encode($hot_words));
        }catch (\Exception $e){

            Log::channel("error")->info($e->getMessage());
        }


    }
}
