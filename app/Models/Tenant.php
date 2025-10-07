<?php
// app/Models/Tenant.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tenant extends Model
{
    use HasFactory;

    protected $fillable = [
        'property_id', 'unit_id', 'name', 'email', 'phone', 'id_number',
        'lease_start_date', 'lease_end_date', 'rent_balance', 
        'deposit_balance', 'status', 'emergency_contact'
    ];

    protected $casts = [
        'lease_start_date' => 'date',
        'lease_end_date' => 'date',
    ];

    public function property()
    {
        return $this->belongsTo(Property::class);
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function getTotalPaidAttribute()
    {
        return $this->payments()->where('status', 'completed')->sum('amount');
    }

    public function getIsRentDueAttribute()
    {
        return $this->rent_balance > 0;
    }
}