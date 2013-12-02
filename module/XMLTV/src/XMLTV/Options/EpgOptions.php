<?php
/**
 * Mizanur Rahman
 *
 * @link      https://www.linkedin.com/pub/mizanur-rahman/32/b15/248
 * @copyright N/A
 */

namespace XMLTV\Options;        

use Zend\Stdlib\AbstractOptions;

/**
 * Class fetches module options
 */
class EpgOptions extends AbstractOptions
{ 
    protected $companyCodes;
    protected $companyNamespaces;
    
    public function getCompanyCodes()  
    {
        return $this->companyCodes;        
    }
    
    public function setCompanyCodes($companyCodes)
    {
        $this->companyCodes = $companyCodes;  
        return $this;          
    }

    public function getCompanyNamespaces()
    {
        return $this->companyNamespaces;
    }

    public function setCompanyNamespaces($companyNamespaces)
    {
        $this->companyNamespaces = $companyNamespaces;
        return $this;
    }
} 
