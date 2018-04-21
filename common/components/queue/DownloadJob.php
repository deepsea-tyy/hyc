<?php
namespace common\components\queue;

class DownloadJob extends \yii\base\BaseObject implements \yii\queue\Job
{
    public $url;
    public $file;
    
    public function execute($queue)
    {
        file_put_contents($this->file, file_get_contents($this->url));
    }
}