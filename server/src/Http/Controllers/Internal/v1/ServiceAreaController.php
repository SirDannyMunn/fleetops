<?php

namespace Fleetbase\FleetOps\Http\Controllers\Internal\v1;

use Fleetbase\FleetOps\Exports\ServiceAreaExport;
use Fleetbase\FleetOps\Http\Controllers\FleetOpsController;
use Fleetbase\FleetOps\Models\Place;
use Fleetbase\Http\Requests\ExportRequest;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;

class ServiceAreaController extends FleetOpsController
{
    /**
     * The resource to query.
     *
     * @var string
     */
    public $resource = 'service_area';

    /**
     * Export the fleets to excel or csv.
     *
     * @return \Illuminate\Http\Response
     */
    public static function export(ExportRequest $request)
    {
        $format       = $request->input('format', 'xlsx');
        $selections   = $request->array('selections');
        $fileName     = trim(Str::slug('service-areas-' . date('Y-m-d-H:i')) . '.' . $format);

        return Excel::download(new ServiceAreaExport($selections), $fileName);
    }

    
    public function checkAddress($id)
    {
        $place = Place::with("deliveryRoute")->firstWhere("public_id", $id);

        return response()->json([
            "service_area" => $place->closestServiceArea()
        ]);
    }
}
