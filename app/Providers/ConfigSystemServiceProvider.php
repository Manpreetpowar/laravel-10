<?php

/** --------------------------------------------------------------------------------
 * This service provider configures the applications theme
 * @package    ShowTec
 * @author     NextLoop
 *----------------------------------------------------------------------------------*/

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class ConfigSystemServiceProvider extends ServiceProvider {

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot() {

        //save system settings into config array
        $setting_model = new \App\Models\Settings;
        $settings = $setting_model::all();
        if(!$settings){
            return;
        }

        foreach($settings as $s)
        {
            $setting[$s->key]=$s->value;

        }
        
        //set timezone
        date_default_timezone_set($setting_model->get_key('settings_system_timezone')->value);
        // date_default_timezone_set('Asia/Singapore');

        // currency symbol position setting
        if ($setting_model->get_key('settings_system_currency_position')->value == 'left') {
            $setting['currency_symbol_left'] = $setting_model->get_key('settings_system_currency_symbol')->value;
            $setting['currency_symbol_right'] = '';
        } else {
            $setting['currency_symbol_right'] = $setting_model->get_key('settings_system_currency_symbol')->value;
            $setting['currency_symbol_left'] = '';
        }


        //Just a list of all payment geteways - used in dropdowns and filters
        $setting['gateways'] = [
            'Paypal',
            'Stripe',
            'Bank',
            'Cash',
        ];

        //cronjob path
    //    $setting['cronjob_path'] = 'php ' . __DIR__ . '/artisan schedule:run 1>> /dev/null 2>&1';

        //javascript file versioning to avoid caching when making updates
        $setting['versioning'] = $setting_model->get_key('settings_system_javascript_versioning')->value;

        
        //save once to config

        config(['system' => $setting]);
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register() {
        //
    }

}
