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
use TvGrabber\Model\Entity\File as FileEntity;
use TvGrabber\Util\DbUtil;

class BoxNationController extends AbstractActionController
{    
    public function indexAction()
    {
        $sm = $this->getServiceLocator(); 

        $fileDir = '/home/mizanur/public_html/xmltv/tests/data/bn';
        $files = $this->scanDirectory($fileDir);
        foreach($files as $file) {
            $sm->get('ZeusService')
                ->registerFile(
                    'boxnation', 
                    'Box Nation', 
                    'epg', 
                    $fileDir . '/' . $file
                );
        }

        $files = $sm->get('FileModel')->getFilesByType(
            'boxnation',
            'Box Nation',
            'epg' 
        );

        echo "----------------------------------------------------------\n";
        echo " Importing EPG data for \"Box Nation\"\n";
        echo "----------------------------------------------------------\n";

        if($files){
            foreach($files as $file) {
                $sm->get('ZeusService')
                    ->processXml(37, 'boxnation', 63, 'Box Nation', $file->filePath);
            }
        }else{
            echo " Nothing to ingest\n\n\n";
        }
    }
    
    private function scanDirectory($outerDir)
    { 
        $dirs = array_diff(scandir($outerDir), array( ".", ".." )); 
        $dir_array = array(); 
        foreach( $dirs as $d ){ 
            if( is_dir($outerDir."/".$d) ) 
                $dir_array[$d] = $this->scanDirectory($outerDir."/".$d);
            else 
                $dir_array[$d] = $d; 
        } 
        return $dir_array; 
    }
}
