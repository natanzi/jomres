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

class jomres_countries
{
    public function __construct()
    {
        $this->countries = false;
        $this->get_countries();
    }

    public function get_countries()
    {
		if (is_object($this->countries)) {
			return true;
		}
		
		$this->countries = new stdClass();

        $query = "SELECT `id`,`countrycode`,`countryname` FROM #__jomres_countries ORDER BY `countryname`";
		$result = doSelectSql($query);

		if (!empty($result)) {
			foreach ($result as $country) {
				$country_code = strtoupper($country->countrycode);
				
				$this->countries->$country_code = $country;
				
				$this->countries->$country_code->countryname = jr_gettext('_JOMRES_CUSTOMTEXT_COUNTRIES_'.$country->id, $country->countryname, false);
			}
        }

        return true;
    }

    public function get_country_by_id($id)
    {
        foreach ($this->countries as $c) {
            if ($c->id == $id) {
                return $c;
            }
        }

        return false;
    }
}
