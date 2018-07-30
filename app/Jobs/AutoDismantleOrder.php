<?php

namespace App\Jobs;

use App\controllers\SupplierOrderController;
use App\lib\ApiResponseByLog;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;

class AutoDismantleOrder implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;

    /**
     * 需要拆分的平台订单id
     * @var int
     */
    public $plat_order_id;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($id)
    {
        $this->plat_order_id = $id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(ApiResponseByLog $apiResponseByLog)
    {
        $soc = new SupplierOrderController($apiResponseByLog);
        
        $soc->orderSplit($this->plat_order_id);
    }

    /**
     * The job failed to process.
     *
     * @param  \Exception $exception
     * @return void
     */
    public function failed(\Exception $e)
    {
        Log::error('平台订单拆单失败, 订单id为:' . $this->plat_order_id . ', 错误信息:' . $e->getMessage());
    }
}
