<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DateTimeInterface;

class Nomina extends Model
{
    protected $guarded = ["id"];

    public function empleado()
    {
        return $this->belongsTo('App\User','empleado_id');
    }

    public function movimientos(){
        return $this->morphMany(Movimiento::class,'movimientoable');
    }

    /**
     * Prepare a date for array / JSON serialization.
     *
     * @param  \DateTimeInterface  $date
     * @return string
     */
    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
}
