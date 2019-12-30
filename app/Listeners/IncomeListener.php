<?php

namespace App\Listeners;

use App\Events\IncomeEvent;
use App\Model\Asset;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;

class IncomeListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  IncomeEvent  $event
     * @return void
     */
    public function handle(IncomeEvent $event)
    {
        //

        $income = $event->income;
        $data = $event->data;
        $asset = new Asset();
        foreach($data as $k=>$v){
            $amount = $asset->getBalance($v['user_id']);
            $data[$k]['surplus_amount'] = $amount;
        }


        $income->insertIncome($data);

    }
}
