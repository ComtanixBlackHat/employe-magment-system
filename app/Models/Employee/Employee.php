<?php

namespace App\Models\Employee;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Institution\Institution;
use App\Models\User;
class Employee extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'institution_id',
        'name',
        'address',
        'date_of_birth',
        'place_of_birth',
        'certification',
        'government_id',
        'picture',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'date_of_birth' => 'date',
    ];

    /**
     * Get the user that owns the employee.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the institution that owns the employee.
     */
    public function institution()
    {
        return $this->belongsTo(Institution::class);
    }
}
