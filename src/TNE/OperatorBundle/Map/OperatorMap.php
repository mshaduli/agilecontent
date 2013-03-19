<?php

namespace TNE\OperatorBundle\Map;

use Vich\GeographicalBundle\Map\Map;

/**
 * Description of AccommodationOperatorMap
 *
 * @author zuhairnaqvi
 */
class OperatorMap extends Map {

    public function __construct()
    {
        parent::__construct();

        $this->setAutoZoom(true);
        $this->setContainerId('map_canvas');
        $this->setWidth("100%");
        $this->setHeight(350);
    }
}
