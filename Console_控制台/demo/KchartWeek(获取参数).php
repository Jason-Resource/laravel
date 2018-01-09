<?php namespace App\Console\Commands;

use App\Exceptions\JsonException;
use App\Http\Business\KchartBusiness;
use App\Http\Business\StockRemoteBusiness;
use Illuminate\Console\Command;

class KchartWeek extends Command {

    /**
     * The console command name.
     *
     * @var string
     */
//    protected $name = 'kchart:date';

    protected $signature = 'kchart:week {code} {start?} {end?}';

    private $kchart_business = null;
    private $stock_remote_business = null;

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '导入周K线图数据';

    public function __construct(KchartBusiness $kchart_business, StockRemoteBusiness $stock_remote_business)
    {
        parent::__construct();
        $this->kchart_business = $kchart_business;
        $this->stock_remote_business = $stock_remote_business;
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function fire()
    {
        //股票代码
        $stock_code = $this->argument('code');
        //开始日期(包含当天)
        $start = $this->argument('start');
        //结束日期(包含当天)
        $end = $this->argument('end');
        
        if (empty($stock_code)) {
            $this->info("参数错误");
            throw new JsonException(10000);
        }

        //获取K线图数据
        $condition = [];
        $condition['code'] = $stock_code;
        if (!empty($start)) {
            $condition['start'] = date('Y-m-d', strtotime($start));
        }
        if (!empty($end)) {
            $condition['end'] = date('Y-m-d', strtotime($end));
        }
        $kchart_data = $this->stock_remote_business->kchartWeek($condition);
        
        //处理数据
        $kchart_data_handle = $this->kchart_business->handleKchartWeek($kchart_data, $stock_code);

        //添加
        $response = $this->kchart_business->batchStoreWeek($kchart_data_handle);

        $this->info("succ");
    }

}