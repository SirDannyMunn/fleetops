<?php

namespace Fleetbase\FleetOps\Models;

use Fleetbase\Models\Model;
use Fleetbase\Traits\HasApiModelBehavior;
use Fleetbase\Traits\HasInternalId;
use Fleetbase\Traits\HasPublicId;
use Fleetbase\Traits\HasUuid;
use Fleetbase\Traits\TracksApiCredential;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Spatie\Activitylog\Traits\CausesActivity;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Sluggable\HasSlug;


class DeliveryRoute extends Model
{
    use HasUuid;
    use HasPublicId;
    use HasInternalId;
    use TracksApiCredential;
    use HasApiModelBehavior;

    protected $table = 'delivery_routes';

    protected $fillable = [
        'name',
        'uuid',
        'service_area_uuid',
        'start_time',
        'end_time',
        'days_of_week',
        'repeat_frequency',
        'start_date',
        'end_date',
        'is_active',
        'company_uuid', // Add this line
    ];

    protected $casts = [
        'days_of_week' => 'array', // JSON field
        'is_active' => 'boolean',
        'start_time' => 'datetime:H:i:s',
        'end_time' => 'datetime:H:i:s',
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    // Automatically generate UUID
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->uuid)) {
                $model->uuid = (string) Str::uuid();
            }
        });
    }

    // Relationships
    public function serviceArea()
    {
        return $this->belongsTo(ServiceArea::class, 'service_area_uuid', 'uuid');
    }

    
    public function orders()
    {
        return $this->hasMany(Order::class, 'delivery_route_uuid', 'uuid');
    }

}
