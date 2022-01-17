<?php

namespace App\Models;

use Core\Model;

class SettingsModel extends Model
{
    public $settings;

    public $basic;

    /**
     * BookingModel constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->settings = $this->table('tbl_settings')->get(\PDO::FETCH_ASSOC);

        $this->basic = unserialize($this->settings[0]['settings_value']);
    }

    public function currency()
    {
        $currency = $this->basic['currency'];
        if ($currency == "лв."){
            $currency = tr_('лв.');
        }
        return $currency;
    }

    public function __get($name)
    {
        if (isset($this->basic[$name])){
            return $this->basic[$name];

        } else{
            throw new \Exception("Not Hotel settings name found", 500);
        }
    }

}