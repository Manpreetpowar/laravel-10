<?php

/** --------------------------------------------------------------------------------
 * This repository class manages all the data absctration for application global setting
 *
 * @package    ABS
 * @author     Rohit
 *----------------------------------------------------------------------------------*/

namespace App\Repositories;

use Illuminate\Http\Request;
use App\Models\Setting;
use Log;

class SettingRepository {

    /** Setting models */
    protected $setting;

    public function __construct(Setting $setting){
        // set valirables
        $this->setting = $setting;
    }

    /**
     * get all categories on a given type
     * @param string $type the type of the category
     * @return object
     */
    public function get($id=null){

        //new object
        $query = $this->setting->Query();
        //filters
        if($id){
            $query->where('id',$id);
        }
        //get
        return $query;
    }

    public function update()
    {
        try {
            $data = request()->only(['settings_expenses_category','settings_gst_percentage','settings_service_notification_email']);

            foreach ($data as $key => $value) {
                $this->setting->where('key', $key)->update(['value' => $value]);
            }
            return true;
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }
}
