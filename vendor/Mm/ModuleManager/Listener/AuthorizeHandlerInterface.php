<?php
namespace Mm\ModuleManager\Listener;

interface AuthorizeHandlerInterface
{
    public function authorizeModule($module);
}
