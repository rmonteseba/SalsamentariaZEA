<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DateTimeInterface;

class Movimiento extends Model
{
    protected $fillable = ["parteCrediticia", "parteEfectiva"];

    const INGRESO = "Ingreso";
    const EGRESO = "Egreso";

    public function movimientoable()
    {
        return $this->morphTo()->withTrashed();
    }

    public function caja()
    {
        return $this->belongsTo('App\Caja');
    }

    /**
     * Prepare a date for array / JSON serialization.
     *
     * @param \DateTimeInterface $date
     * @return string
     */
    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
}
