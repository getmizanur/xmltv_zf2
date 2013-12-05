<?php
/**
 * Mizanur Rahman
 *
 * @link      https://www.linkedin.com/pub/mizanur-rahman/32/b15/248
 * @copyright N/A
 */

namespace TvGrabber\Options;        

use Zend\Stdlib\AbstractOptions;

/**
 * Module option class         
 */
class EbsOptions extends AbstractOptions
{ 
    /**
     * @var CurrencyRateOptions
     */
    protected $epgOptions; 
    
    /**
     * Get CurrencyRateOptions class
     *
     * @return CurrencyRateOptions
     */
    public function getEpgOptions()
    {
        return $this->epgOptions;   
    }
    
    /** 
     * Set CurrencyRateOptions class
     *  
     * @param CurrencyRateOptions $currencyRateOptions
     * @return $this
     */ 
    public function setEpgOptions($epgOptions)
    {   
        $this->epgOptions = new EpgOptions($epgOptions);
        return $this;          
    }     
}   
