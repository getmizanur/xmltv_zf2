<?php
/**
 * Mizanur Rahman
 *
 * @link      https://www.linkedin.com/pub/mizanur-rahman/32/b15/248
 * @copyright N/A
 */

namespace Epg\Model\Service;

use Zend\EventManager\EventManager;
use Zend\EventManager\EventManagerInterface;
use Zend\EventManager\EventManagerAwareInterface;

use Epg\Model\Entity\Epg as EpgEntity;
use Epg\Model\Entity\File as FileEntity;
use Epg\Model\Table\EpgTable;

class EpgService extends AbstractService implements
    EventManagerAwareInterface
{
    protected $events;

    public function setEventManager(EventManagerInterface $events)
    {
        $events->setIdentifiers(array(
            __CLASS__,
            get_called_class(),
        ));
        $this->events = $events;
        return $this;
    }

    public function getEventManager() 
    {
        if(null === $this->events) {
            $this->setEventManager(new EventManager());
        }

        return $this->events;
    }

    public function getEpgData($service)
    {
        $sm = $this->getServiceLocator(); 

        $channels = $sm->get('Epg\Model\Table\EpgModel')
            ->getEpgChannels($service);

        $this->getEventManager()->trigger('preTest', __CLASS__, array('post' => 'post'));

        $data = array();
        foreach($channels as $channel) {
            $programmes = $sm->get('Epg\Model\Table\EpgModel')
                ->getProgrammesByService($service, $channel);

            $data[] = array (
                'schedule_date' => date('Y-m-d'),
                'start' => ($programmes && $programmes[0] ? 
                    date(DATE_ATOM, strtotime($programmes[0]['epg_start'])) : null),
                'end' => ($programmes && $programmes[count($programmes) - 1] ? 
                    date(DATE_ATOM, 
                        strtotime($programmes[count($programmes) - 1]['epg_start']) + 
                        $programmes[count($programmes) - 1]['epg_duration']
                    ) : null),
                'service' => array(
                    'type' => 'tv',
                    'id' => str_replace(' ', '_', strtolower($channel)),
                    'key' => str_replace(' ', '', strtolower($channel)),
                    'title' => $channel,
                ),
                'programmes' => $programmes
            );
        }

        $this->getEventManager()->trigger('postTest', __CLASS__);

        return $data;
    }
}
