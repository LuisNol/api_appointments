<?php

namespace App\Models\DocumentType;

use App\Models\Patient\Patient;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DocumentType extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
    ];

    // Opcional: si deseas controlar las fechas manualmente
    public function setCreatedAtAttribute($value)
    {
        date_default_timezone_set('America/Lima');
        $this->attributes['created_at'] = Carbon::now();
    }

    public function setUpdatedAtAttribute($value)
    {
        date_default_timezone_set('America/Lima');
        $this->attributes['updated_at'] = Carbon::now();
    }

    // Relación con pacientes, si deseas establecerla
    public function patients()
    {
        return $this->hasMany(Patient::class, 'document_type_id');
    }
}
