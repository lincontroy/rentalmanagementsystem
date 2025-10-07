<?php
// app/Models/Property.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Property extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'paybill_number', 'address', 'total_units', 
        'occupied_units', 'monthly_rent_total','user_id', 'is_active'
    ];

    public function units()
    {
        return $this->hasMany(Unit::class);
    }

    public function tenants()
    {
        return $this->hasMany(Tenant::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function getOccupancyRateAttribute()
    {
        if ($this->total_units == 0) return 0;
        return ($this->occupied_units / $this->total_units) * 100;
    }
}