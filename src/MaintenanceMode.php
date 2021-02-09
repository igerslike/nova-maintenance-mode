<?php

namespace Davidpiesse\NovaMaintenanceMode;

/**
 * Class to wrap Artisan Maintenance mode logic
 */
class MaintenanceMode
{
    /**
     * Bring Application out of Maintenance Mode
     *
     * @return void
     */
    public static function up(){
        \Artisan::call(sprintf('up'));
        return;
    }

    /**
     * Put Application into Maintenance Mode
     *
     * @param Request $request
     * @return void
     */
    public static function down($request){

        $props = $request->only(['message', 'retry', 'allow','include_current_ip']);
        $retry = data_get($props, 'retry');
        $retry_seconds = is_numeric($retry) && $retry > 0 ? (int) $retry : null;

        \Artisan::call(
            sprintf(
                'down --render="errors::503" --secret="%s" --retry=%s',
                env('MAINTENANCE_SECRET','secret-in-maintenance'),
                $retry_seconds
            )
        );
        return;
    }


}