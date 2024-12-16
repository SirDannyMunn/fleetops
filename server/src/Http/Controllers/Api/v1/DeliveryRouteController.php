<?php

namespace Fleetbase\FleetOps\Http\Controllers\Api\v1;

use Illuminate\Http\Request;
use Fleetbase\FleetOps\Models\DeliveryRoute;
use Fleetbase\Http\Controllers\Controller;

class DeliveryRouteController extends Controller
{

    // List all delivery routes
    public function query()
    {
        $deliveryRoutes = DeliveryRoute::with('serviceArea', 'orders')->get();
        return response()->json($deliveryRoutes);
    }

    // Show a specific delivery route
    public function find($id)
    {
        $deliveryRoute = DeliveryRoute::with('serviceArea', 'orders')->findOrFail($id);
        return response()->json($deliveryRoute);
    }

    // Create a new delivery route
    public function create(Request $request)
    {
        $data = $request->validate([
            'name'               => 'required|string',
            'service_area_uuid'  => 'required|string|exists:service_areas,uuid',
            'start_time'         => 'required',
            'end_time'           => 'nullable',
            'days_of_week'       => 'required|array',
            'repeat_frequency'   => 'required|in:daily,weekly,monthly,yearly,custom',
            'start_date'         => 'nullable|date',
            'end_date'           => 'nullable|date',
            'is_active'          => 'boolean',
        ]);

        $deliveryRoute = DeliveryRoute::create(array_merge($data,
            ['company_uuid' => session('company')]
        ));

        return response()->json($deliveryRoute, 201);
    }

    // Update an existing delivery route
    public function update(Request $request, $id)
    {
        $deliveryRoute = DeliveryRoute::findOrFail($id);

        $data = $request->validate([
            'name'               => 'sometimes|required|string',
            'service_area_uuid'  => 'sometimes|required|string|exists:service_areas,uuid',
            'start_time'         => 'sometimes|required',
            'end_time'           => 'nullable',
            'days_of_week'       => 'sometimes|required|array',
            'repeat_frequency'   => 'sometimes|required|in:daily,weekly,monthly,yearly,custom',
            'start_date'         => 'nullable|date',
            'end_date'           => 'nullable|date',
            'is_active'          => 'boolean',
        ]);

        $deliveryRoute->update($data);

        return response()->json($deliveryRoute);
    }

    // Delete a delivery route
    public function delete($id)
    {
        $deliveryRoute = DeliveryRoute::findOrFail($id);
        $deliveryRoute->delete();

        return response()->json(['message' => 'Delivery route deleted successfully']);
    }
}
