<?php
namespace Mm\View\Helper;

use Zend\View\Helper\AbstractHelper,
use Application\Controller\AbstractController as BaseController;

class FlashMessenger extends AbstractHelper
{
    const VALID_MESSAGE = 'valid';
    const ERROR_MESSAGE = 'error';
    const DEFAULT_MESSAGE = 'error';
    
    public function __invoke(array $types)
    {
        $message = '';
        $view = $this->getView();
        
        if(in_array(self::VALID_MESSAGE, $types)) {
            $validMessages = $view->get(BaseController::VALID_FLASH_MESSENGER);
            if($validMessages) {
                $message = '<div class="alert alert-success">';
                foreach($validMessages as $validMessage) {
                    $message .= $validMessage."<br />";
                }
                $message .= '</div>';
            }
        }
        
        if(in_array(self::ERROR_MESSAGE, $types)) {
            $errorMessages = $view->get(BaseController::ERROR_FLASH_MESSENGER);
            if($errorMessages) {
                $message = '<div class="alert alert-error">';
                foreach($errorMessages as $errorMessage) {
                    $message .= $errorMessage."<br />";
                }
                $message .= '</div>';
            }
        }
        
        if(in_array(self::DEFAULT_MESSAGE, $types)) {
            $defaultsMessages = $view->get(BaseController::DEFAULT_FLASH_MESSENGER);
            if($defaultsMessages) {
                $message = '<div class="alert alert-info">';
                foreach($defaultsMessages as $defaultMessage) {
                    $message .= $defaultMessage."<br />";
                }
                $message .= '</div>';
            }
        }
        
        return $message;
    }
}
