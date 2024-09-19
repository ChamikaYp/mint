<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class JobScope extends Model
{
    use HasFactory;

    protected $casts = [
        'lawn_care' => 'boolean',
        'trimming' => 'boolean',
        'raking_up_leaf_litter' => 'boolean',
        'weeding' => 'boolean',
        'blowing' => 'boolean',
        'clearing_garden_beds' => 'boolean',
        'collecting_litter' => 'boolean',
        'entrance_glass_cleaning' => 'boolean',
        'bringing_in_bins' => 'boolean',
        'remove_cob_webs' => 'boolean',
        'vacuum' => 'boolean',
        'clear_out_bin_corral' => 'boolean',
        'clearing_drainage_gate' => 'boolean',
        'vacuum_and_mop_laundry' => 'boolean',
        'remove_waste_laundry' => 'boolean',
    ];

    protected $fillable = [
        'job_id',
        'lawn_care',
        'trimming',
        'raking_up_leaf_litter',
        'weeding',
        'blowing',
        'clearing_garden_beds',
        'collecting_litter',
        'entrance_glass_cleaning',
        'bringing_in_bins',
        'remove_cob_webs',
        'vacuum',
        'clear_out_bin_corral',
        'clearing_drainage_gate',
        'vacuum_and_mop_laundry',
        'remove_waste_laundry',
    ];

    public function job(): BelongsTo
    {
        return $this->belongsTo(Job::class);
    }
}
