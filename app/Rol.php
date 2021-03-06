<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DateTimeInterface;

class Rol extends Model
{
    protected $fillable = ['id', 'nombre'];

    public function users()
    {
        return $this->hasMany('App\User');
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
