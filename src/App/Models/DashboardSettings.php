<?php

namespace App\Models;

use Core\Model;

class DashboardSettings extends Model
{

    /**
     * BookingModel constructor.
     */
    public function __construct()
    {
        parent::__construct(['table'=>'']);
    }

    public function fetch_profile()
    {
        $data = $this->table('tbl_settings')
            ->where('settings_name', '=', 'basic')
            ->get(\PDO::FETCH_ASSOC);

        $_result =  unserialize($data[0]['settings_value']);

        foreach($_result as $k=>$v){
            $result[$k] = html_entity_decode($v);
        }

        return $result;
    }

    public function fetch_basic()
    {
        $data = $this->table('tbl_settings')
            ->where('settings_name', '=', 'basic')
            ->get(\PDO::FETCH_ASSOC);

        $_result =  unserialize($data[0]['settings_value']);

        foreach($_result as $k=>$v){
            $result[$k] = html_entity_decode($v);
        }
        return $result;
    }
}