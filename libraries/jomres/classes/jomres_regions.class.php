<?php
/**
 * Core file.
 *
 * @author Vince Wooll <sales@jomres.net>
 *
 * @version Jomres 9.8.18
 *
 * @copyright	2005-2016 Vince Wooll
 * Jomres (tm) PHP, CSS & Javascript files are released under both MIT and GPL2 licenses. This means that you can choose the license that best suits your project, and use it accordingly
 **/

// ################################################################
defined('_JOMRES_INITCHECK') or die('');
// ################################################################

class jomres_regions
{
    public function __construct()
    {
        $this->regions = false;
        $this->get_regions();
    }

    public function get_regions()
    {
        $performance_monitor = jomres_singleton_abstract::getInstance('jomres_performance_monitor');
        $performance_monitor->set_point("Setting up regions. Let's ensure we're not doing this more than once.");

		if (is_object($this->regions)) {
			return true;
		}
		
		$this->regions = new stdClass();

		$performance_monitor->set_point('Setting regions from db.');
            
		$siteConfig = jomres_singleton_abstract::getInstance('jomres_config_site_singleton');
		$jrConfig = $siteConfig->get();
		
		if (!isset($jrConfig[ 'region_names_are_translatable' ])) {
			$jrConfig[ 'region_names_are_translatable' ] = 0;
		}

		$query = "SELECT `id`,`countrycode`,`regionname` FROM #__jomres_regions ORDER BY `countrycode`,`regionname`";
		$result = doSelectSql($query);
		
		if (!empty($result)) {
			foreach ($result as $region) {
				$this->regions->{$region->id} = $region;
				if ((int)$jrConfig[ 'region_names_are_translatable' ] == 1) {
					$this->regions->{$region->id}->regionname = jr_gettext('_JOMRES_CUSTOMTEXT_REGIONS_'.$region->id, $region->regionname, false);
				}
			}
        }

        $performance_monitor->set_point('Region generation done.');

        return true;
    }

	//not used TODO: to be removed 
    /* public function get_country_regions($countrycode)
    {
        foreach ($this->regions as $region) {
            if ($region->countrycode == $countrycode) {
                return $region;
            }
        }

        return false;
    } */

    public function get_region_by_id($id)
    {
        if (isset($this->regions->$id)) {
            return $this->regions->$id;
        } else {
            throw new Exception('Tried to get region with non-existant id');
        }
    }
}
