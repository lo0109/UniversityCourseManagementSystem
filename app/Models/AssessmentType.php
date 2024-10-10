<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Assessment;

class AssessmentType extends Model
{
    use HasFactory;

    protected $fillable = ['type']; 

    // Define any relationships here if needed
    public function assessments()
    {
        return $this->hasMany(Assessment::class, 'typeID');
    }
}
