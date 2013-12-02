<?php
namespace Mm\ModuleManager\Listener;

use Zend\ModuleManager\Listener\ListenerOptions as BaseListener;

class ListenerOptions extends BaseListener
{
    protected $lazyLoading = array();
    
    public function getLazyLoading()
    {
        return $this->lazyLoading;
    }

    public function setLazyLoading(array $lazyLoading)
    {
        $this->lazyLoading = $lazyLoading;
        return $this;
    }
}
