<?php
// app/Models/Unit.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Unit extends Model
{
    use HasFactory;

    protected $fillable = [
        'property_id', 'unit_number', 'rent_amount', 
        'deposit_amount', 'status', 'description'
    ];

    public function property()
    {
        return $this->belongsTo(Property::class);
    }

    public function tenant()
    {
        return $this->hasOne(Tenant::class);
    }

    public function tenants()
    {
        return $this->hasMany(Tenant::class);
    }

    public function payments()
    {
        return $this->hasManyThrough(Payment::class, Tenant::class);
    }

    public function isOccupied()
    {
        return $this->status === 'occupied';
    }

    public function isVacant()
    {
        return $this->status === 'vacant';
    }

    public function isUnderMaintenance()
    {
        return $this->status === 'maintenance';
    }

    public function getCurrentTenantAttribute()
    {
        return $this->tenant()->where('status', 'active')->first();
    }
}