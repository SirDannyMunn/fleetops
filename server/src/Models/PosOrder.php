<?php

namespace Fleetbase\FleetOps\Models;

use Illuminate\Database\Eloquent\Model;

class PosOrder extends Model
{
    protected $connection = 'POS';
    protected $table = 'ns_nexopos_orders';

    /**
     * Set the tenant database for this model instance
     * 
     * @param string $tenant
     * @return self
     */
    public function setTenant(string $tenant)
    {
        config(["database.connections.POS.database" => "tenant" . $tenant]);
        
        // Purge the connection to force Laravel to reconnect with new config
        \DB::purge('POS');
        
        return $this;
    }

    /**
     * Create a new instance with tenant configuration
     * 
     * @param string $tenant
     * @return static
     */
    public static function forTenant(string $tenant)
    {
        $instance = new static;
        return $instance->setTenant($tenant);
    }
}