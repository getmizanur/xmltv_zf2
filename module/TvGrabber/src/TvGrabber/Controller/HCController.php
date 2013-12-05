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

class HCController extends AbstractActionController
{    
    public function indexAction()
    {
        $sm = $this->getServiceLocator(); 

        $fileDir = '/home/mizanur/public_html/xmltv/tests/data/hc';
        $files = $this->scanDirectory($fileDir);
        foreach($files as $file) {
            $sm->get('TvAnyTimeService')
                ->registerFile(
                    'horseandcountry', 
                    'Horse and Country', 
                    'epg', 
                    $fileDir . '/' . $file
                );
        }

        $files = $sm->get('FileModel')->getFilesByType(
            'horseandcountry',
            'Horse and Country',
            'epg' 
        );

        echo "----------------------------------------------------------\n";
        echo " Importing EPG data for \"Horse and Country\"\n";
        echo "----------------------------------------------------------\n";

        if($files){
            foreach($files as $file) {
                $sm->get('TvAnyTimeService')
                    ->processXml(25, 'horseandcountry', 164, 'Horse and Country', $file->filePath);
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
