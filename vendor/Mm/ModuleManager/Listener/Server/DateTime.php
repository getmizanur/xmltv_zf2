<?php
namespace Mm\ModuleManager\Listener\Server;

use Mm\ModuleManager\Listener\AbstractListener;

class DateTime extends AbstractListener
{
    public function authorizeModule($moduleName)
    {
        $date = date('Y-m-d H:i:s');
        if($date >= $this->config['start'] && $date <= $this->config['stop']) {
            return true;
        }
        return false;
    }
}
