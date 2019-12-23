<?php

namespace App\Http\Controllers\Api;

use App\Model\Config;
use App\Http\Controllers\Controller;
use App\Model\WithdrawLog;

class WithdrawController extends Controller
{
    private $config;
    private $withdraw;
    public function __construct(WithdrawLog $withdraw,Config $config)
    {
        $this->withdraw = $withdraw;
        $this->config = $config;
    }






}