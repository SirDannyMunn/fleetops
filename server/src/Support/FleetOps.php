<?php

namespace Fleetbase\FleetOps\Support;

use Fleetbase\FleetOps\Models\OrderConfig;
use Fleetbase\Models\Company;
use Illuminate\Support\Str;

class FleetOps
{
    /**
     * Creates or retrieves an existing transport configuration for a given company.
     *
     * This method attempts to find a transport configuration (`OrderConfig`) for the specified company.
     * If such a configuration exists, it's returned. Otherwise, a new configuration is created with default values,
     * such as the company UUID, key, core service flag, status, version, tags, and predefined workflow steps.
     * These steps include 'created', 'enroute', 'started', 'completed', and 'dispatched', each with specific attributes.
     *
     * @param Company $company the company for which the transport configuration is being created or retrieved
     *
     * @return OrderConfig the transport configuration associated with the specified company
     */
    public static function createTransportConfig(Company $company): OrderConfig
    {
        return OrderConfig::firstOrCreate(
            [
                'company_uuid' => $company->uuid,
                'key'          => 'transport',
                'namespace'    => 'system:order-config:transport',
            ],
            [
                'name'         => 'Transport',
                'key'          => 'transport',
                'namespace'    => 'system:order-config:transport',
                'description'  => 'Default order configuration for transport',
                'core_service' => 1,
                'status'       => 'private',
                'version'      => '0.0.1',
                'tags'         => ['transport', 'delivery'],
                'entities'     => [],
                'meta'         => [],
                'flow'         => [
                    'created' => [
                        'key'         => 'created',
                        'code'        => 'created', 
                        'color'       => '#1f2937',
                        'logic'       => [],
                        'events'      => [],
                        'status'      => 'Order Created',
                        'actions'     => [],
                        'details'     => 'New order was created.',
                        'options'     => [],
                        'complete'    => false,
                        'entities'    => [],
                        'sequence'    => 0,
                        'activities'  => ['en_route_to_collection'],
                        'internalId'  => Str::uuid(),
                        'pod_method'  => 'scan',
                        'require_pod' => false,
                    ],
                    'started' => [
                        'key'         => 'loaded',
                        'code'        => 'started',
                        'color'       => '#1f2937',
                        'logic'       => [],
                        'events'      => [],
                        'status'      => 'Order Loaded',
                        'actions'     => [],
                        'details'     => 'Order has been started',
                        'options'     => [],
                        'complete'    => false,
                        'entities'    => [],
                        'sequence'    => 0,
                        'activities'  => ['en_route_to_collection'],
                        'internalId'  => Str::uuid(),
                        'pod_method'  => 'scan',
                        'require_pod' => false,
                    ],
                    'dispatched' => [
                        'key'         => 'dispatched',
                        'code'        => 'dispatched',
                        'color'       => '#1f2937',
                        'logic'       => [],
                        'events'      => [],
                        'status'      => 'Order Dispatched',
                        'actions'     => [],
                        'details'     => 'Order has been dispatched.',
                        'options'     => [],
                        'complete'    => false,
                        'entities'    => [],
                        'sequence'    => 0,
                        'activities'  => ['started'],
                        'internalId'  => Str::uuid(),
                        'pod_method'  => 'scan',
                        'require_pod' => false,
                    ],
                    'order_in_truck' => [
                        'key'         => 'order_in_truck',
                        'code'        => 'order_in_truck',
                        'color'       => '#1f2937',
                        'logic'       => [],
                        'events'      => [],
                        'status'      => 'Order In Truck',
                        'actions'     => [],
                        'details'     => 'Order is in the truck',
                        'options'     => [],
                        'complete'    => false,
                        'entities'    => [],
                        'sequence'    => 0,
                        'activities'  => ['en_route_to_dropoff'],
                        'internalId'  => Str::uuid(),
                        'pod_method'  => 'scan',
                        'require_pod' => false,
                    ],
                    'order_collected' => [
                        'key'         => 'order_collected',
                        'code'        => 'order_collected',
                        'color'       => '#1f2937',
                        'logic'       => [],
                        'events'      => [],
                        'status'      => 'Order Collected',
                        'actions'     => [],
                        'details'     => 'Details',
                        'options'     => [],
                        'complete'    => false,
                        'entities'    => [],
                        'sequence'    => 0,
                        'activities'  => ['dropped_at_depot'],
                        'internalId'  => Str::uuid(),
                        'pod_method'  => 'scan',
                        'require_pod' => false,
                    ],
                    'dropped_at_depot' => [
                        'key'         => 'dropped_at_depot',
                        'code'        => 'dropped_at_depot',
                        'color'       => '#1f2937',
                        'logic'       => [],
                        'events'      => [],
                        'status'      => 'Dropped At Depot',
                        'actions'     => [],
                        'details'     => 'Details',
                        'options'     => [],
                        'complete'    => false,
                        'entities'    => [],
                        'sequence'    => 0,
                        'activities'  => ['order_processing'],
                        'internalId'  => Str::uuid(),
                        'pod_method'  => 'scan',
                        'require_pod' => false,
                    ],
                    'order_processing' => [
                        'key'         => 'order_processing',
                        'code'        => 'order_processing',
                        'color'       => '#1f2937',
                        'logic'       => [],
                        'events'      => [],
                        'status'      => 'Order Processing',
                        'actions'     => [],
                        'details'     => 'Order is being processed at the depot.',
                        'options'     => [],
                        'complete'    => false,
                        'entities'    => [],
                        'sequence'    => 0,
                        'activities'  => ['ready_for_dispatch'],
                        'internalId'  => Str::uuid(),
                        'pod_method'  => 'scan',
                        'require_pod' => false,
                    ],
                    'dropped_at_dropoff' => [
                        'key'         => 'dropped_at_dropoff',
                        'code'        => 'dropped_at_dropoff',
                        'color'       => '#1f2937',
                        'logic'       => [],
                        'events'      => [],
                        'status'      => 'Dropped At Dropoff',
                        'actions'     => [],
                        'details'     => 'Details',
                        'options'     => [],
                        'complete'    => true,
                        'entities'    => [],
                        'sequence'    => 0,
                        'activities'  => [],
                        'internalId'  => Str::uuid(),
                        'pod_method'  => 'scan',
                        'require_pod' => false,
                    ],
                    'ready_for_dispatch' => [
                        'key'         => 'ready_for_dispatch',
                        'code'        => 'ready_for_dispatch',
                        'color'       => '#1f2937',
                        'logic'       => [],
                        'events'      => [],
                        'status'      => 'Ready For Dispatch',
                        'actions'     => [],
                        'details'     => 'Order is ready to be loaded onto the truck.',
                        'options'     => [],
                        'complete'    => false,
                        'entities'    => [],
                        'sequence'    => 0,
                        'activities'  => ['order_in_truck'],
                        'internalId'  => Str::uuid(),
                        'pod_method'  => 'scan',
                        'require_pod' => false,
                    ],
                    'en_route_to_dropoff' => [
                        'key'         => 'en_route_to_dropoff',
                        'code'        => 'en_route_to_dropoff',
                        'color'       => '#1f2937',
                        'logic'       => [],
                        'events'      => [],
                        'status'      => 'En Route To Dropoff',
                        'actions'     => [],
                        'details'     => 'Details',
                        'options'     => [],
                        'complete'    => false,
                        'entities'    => [],
                        'sequence'    => 0,
                        'activities'  => ['dropped_at_dropoff'],
                        'internalId'  => Str::uuid(),
                        'pod_method'  => 'scan',
                        'require_pod' => false,
                    ],
                    'en_route_to_collection' => [
                        'key'         => 'en_route_to_collection',
                        'code'        => 'en_route_to_collection',
                        'color'       => '#1f2937',
                        'logic'       => [],
                        'events'      => [],
                        'status'      => 'En Route To Collection',
                        'actions'     => [],
                        'details'     => 'Details',
                        'options'     => [],
                        'complete'    => false,
                        'entities'    => [],
                        'sequence'    => 0,
                        'activities'  => ['order_collected'],
                        'internalId'  => Str::uuid(),
                        'pod_method'  => 'scan',
                        'require_pod' => false,
                    ]
                ]
            ]
        );
    }
}
