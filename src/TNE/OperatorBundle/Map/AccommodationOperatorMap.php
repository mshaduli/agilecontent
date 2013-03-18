<?php

namespace TNE\OperatorBundle\Map;

use Vich\GeographicalBundle\Map\Map;

/**
 * Description of AccommodationOperatorMap
 *
 * @author zuhairnaqvi
 */
class AccommodationOperatorMap extends Map {

    public function __construct()
    {
        parent::__construct();

        // configure your map in the constructor 
        // by setting the options

        $this->setAutoZoom(true);
        $this->setContainerId('map_canvas');
        $this->setWidth(500);
        $this->setHeight(350);
    }
}

?>
