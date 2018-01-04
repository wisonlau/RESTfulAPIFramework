<?php
namespace App\Services;

class File
{
    private $path;

    public function __construct()
    {
        $this->path = '../storage/lock/';
    }

    /**
     * lock
     * @param  String  $tmpFileStr 用来作为共享锁文件的文件名
     * @param  Boolean $lockType 锁类型，缺省为false(非阻塞型，也就是一旦加锁失败则直接返回false),设置为true则会一直等待加锁成功才返回
     * @return Boolean 如果加锁成功，则返回锁实例(当使用unlock方法的时候需要这个参数)，加锁失败则返回false
     */
    public function lock($tmpFileStr, $lockType = false)
    {
        if($lockType == false)
            $lockType = LOCK_EX|LOCK_NB;
        $can_write = 0;
        $lockfp = @fopen($this->path . $tmpFileStr . ".lock","w");
        if($lockfp)
        {
            $can_write = @flock($lockfp, $lockType);
        }
        if($can_write)
        {
            return $lockfp;
        }
        else{
            if($lockfp){
                @fclose($lockfp);
                @unlink($this->path . $tmpFileStr.".lock");
            }
            return false;
        }
    }

    /**
     * unlock
     * @param  String  $fp lock方法的返回值
     * @param  String  $tmpFileStr 用来作为共享锁文件的文件名（可以随便起一个名字）
     */
    public function unlock($fp, $tmpFileStr)
    {
        @flock($fp,LOCK_UN);
        @fclose($fp);
        @fclose($fp);
        @unlink($this->path . $tmpFileStr.".lock");
    }
}