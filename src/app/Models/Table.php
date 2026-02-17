<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Table extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'type',
        'capacity',
        'is_combinable',
        'combine_group',
        'sort_order',
        'is_active',
    ];

    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function combinedCapacity(): int
    {
        if (!$this->combine_group) {
            return (int) $this->capacity;
        }

        return (int) static::query()
            ->where('combine_group', $this->combine_group)
            ->sum('capacity');
    }
}
