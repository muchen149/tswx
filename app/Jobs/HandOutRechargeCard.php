<?php

namespace App\Jobs;

use App\controllers\MemberRechargeController;
use App\lib\ApiResponseByLog;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;

class HandOutRechargeCard implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;

    /**
     * 订单数据
     * @var int
     */
    public $plat_order;

    /**
     * 充值活动数据
     * @var int
     */
    public $activity;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($order, $rechargeActivityList)
    {
        $this->plat_order = $order;
        $this->activity = $rechargeActivityList;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(ApiResponseByLog $apiResponseByLog)
    {
        $soc = new MemberRechargeController($apiResponseByLog);
        
        $result = $soc->sendRechargeCard($this->plat_order, $this->activity);
    }

    /**
     * The job failed to process.
     *
     * @param  \Exception $exception
     * @return void
     */
    public function failed(\Exception $e)
    {
        Log::error('充值卡充值失败, 会员id为:' . $this->plat_order->member_id . ', 错误信息:' . $e->getMessage());
    }
}
