<?php

namespace App\Console\Commands;

use App\Http\Common\Helper;
use Illuminate\Console\Command;
use ParagonIE\EasyRSA\KeyPair;

class GenerateRsaKey extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'rsa:generate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '用于生成用户登录所用到 rsa 私钥以及公钥!';

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
     *  生成私钥文件，此处不判断是否可写
     *
     * @return mixed
     */
    public function handle()
    {
        //私钥路径
        $private_key_path = config('rsa.private_key_path');
        //公钥路径
        $public_key_path = config('rsa.public_key_path');
        
        //判断文件是否已经存在
        if(file_exists($private_key_path)){
            $ans = $this->ask('私钥已经存在，是否覆盖？y=>覆盖,其他则不做任何操作！');
            if(Helper::trimAny($ans) != 'y'){
                $this->info('取消操作!');
                return false;
            }
        }
        
        //位数，暂时不然选择了吧
        $length = 2048;
        $keyPair = KeyPair::generateKeyPair($length);
    
        //私钥
        $private_key = $keyPair->getPrivateKey()->getKey();
        
        //公钥
        $public_key= $keyPair->getPublicKey()->getKey();
        
        //覆盖到私钥文件
        file_put_contents($private_key_path,$private_key);
        
        //覆盖到公钥文件
        file_put_contents($public_key_path,$public_key);
    
        //返回信息
        $this->info('私钥地址：'.$private_key_path);
    
        $this->info("私钥:\n".$private_key);
        
        $this->line(':>');
        $this->line(':>');
        
        //返回信息
        $this->info('公钥地址：'.$public_key_path);
    
        $this->info("公钥:\n".$public_key);
    }
}
