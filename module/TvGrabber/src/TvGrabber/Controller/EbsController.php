<?php
/**
 * Mizanur Rahman
 *
 * @link      https://www.linkedin.com/pub/mizanur-rahman/32/b15/248
 * @copyright N/A
 */

namespace TvGrabber\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\InputFilter\Factory;

use TvGrabber\Model\Entity\Epg as EpgEntity;
use TvGrabber\Util\DbUtil;

class EbsController extends AbstractActionController
{    
    public function indexAction()
    {
        $sm = $this->getServiceLocator(); 
        
        $ebsOpt = $sm->get('EbsOpt')
            ->getEpgOptions(); 
        
        $companyCodes = $ebsOpt->getCompanyCodes(); 
        $companyNamespaces = $ebsOpt->getCompanyNamespaces(); 

        if(count($companyCodes) > count($companyNamespaces)) {
            echo "----------------------------------------------------------\n";
            echo " Invalid number of options\n";
            echo "----------------------------------------------------------\n";
            echo "\nTo many company codes compared with company namespaces\n\n";
            exit();
        }elseif(count($companyCodes) < count($companyNamespaces)) {
            echo "----------------------------------------------------------\n";
            echo " Invalid number of options\n";
            echo "----------------------------------------------------------\n";
            echo "\nTo many company namespaces compared with company codes\n\n";
            exit();
        }

        $companies = array_combine($companyCodes, $companyNamespaces);

        $factory = new Factory();       
        $inputFilter = $factory->createInputFilter(array (
            'id' => array (    
                'name' => 'id',                 
                'required' => false,             
                'filters' => array (            
                    array ('name' => 'StripTags'),  
                    array ('name' => 'StringTrim'), 
                    array ('name' => 'HtmlEntities')
                ),                              
                'validators' => array (         
                    array ('name' => 'Int'),
                    array ('name' => 'in_array',           
                        'options' => array (            
                            'haystack' => $companyCodes
                        )
                    )
                )
            ),
        ));
         
        $inputFilter->setData($this->getRequest()->getParams());
        if(!$inputFilter->isValid()) {  
            $companyId = implode(',', $companyCodes); 
            echo "----------------------------------------------------------\n";
            echo "Notice: Invalid company ID\n";
            echo "----------------------------------------------------------\n";
            echo "\nUse --import-epg [id | {$companyId}]>\n\n";
            exit();
        }

        echo "----------------------------------------------------------\n";
        echo " Pulling down XML gzip file from EBS\n";
        echo "----------------------------------------------------------\n";
        echo "\nPulling down XML gzip file and unziping it\n\n";

        exec("rm  " . __DIR__ . '/../../../../../data/ebs/*');
        exec("wget --user=ftpSimpleStream --password='H23k1k@m8*f!DV' ftp://listings.digiguide.tv/SimpleStream.xml.gz -P " . 
            __DIR__ . '/../../../../../data/ebs');
        exec("gunzip -f " .  __DIR__ . '/../../../../../data/ebs/SimpleStream.xml.gz > ' . __DIR__ . '/../../../../../data/ebs/SimpleStream.xml');

        if($inputFilter->getValue('id')) {
           if($companies[$inputFilter->getValue('id')]) {
                echo "----------------------------------------------------------\n";
                echo " Importing EPG data for {$companies[$inputFilter->getValue('id')]}\n";
                echo "----------------------------------------------------------\n";

                $sm->get('XmltvService')->processXml(
                    $inputFilter->getValue('id'), $companies[$inputFilter->getValue('id')]
                );
           }
        }else{
            foreach($companies as $code => $namespace) {
                echo "----------------------------------------------------------\n";
                echo " Importing EPG data for $namespace\n";
                echo "----------------------------------------------------------\n";

                $sm->get('XmltvService')->processXml($code, $namespace);
            }
        }
    }
}
