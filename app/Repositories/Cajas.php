<?php

namespace App\Repositories;

use App\Caja;
use App\Cierre;
use App\Entrada;
use App\Movimiento;
use App\Nomina;
use App\Servicio;
use App\Venta;
use Illuminate\Support\Facades\Log;

class Cajas
{

    /**
     * @param $parteEfectiva
     * @param $parteCrediticia
     * @param $valor
     * @return bool
     */
    public function isMontosPagoValidos($parteEfectiva, $parteCrediticia, $saldo)
    {
        return $parteCrediticia + $parteEfectiva <= $saldo;
    }

    /**
     * Genera un pago y su movimiento asociado
     * @param Caja $caja
     * @param $movimientoable
     * @param int $parteEfectiva
     * @param int $parteCrediticia
     */
    public function pagar(Caja $caja, $movimientoable, $parteEfectiva = 0, $parteCrediticia = 0)
    {
        $nuevoMovimiento = new Movimiento();
        $nuevoMovimiento->parteEfectiva = $parteEfectiva == null ? 0 : $parteEfectiva;
        $nuevoMovimiento->parteCrediticia = $parteCrediticia == null ? 0 : $parteCrediticia;
        $nuevoMovimiento->total = $nuevoMovimiento->parteEfectiva + $nuevoMovimiento->parteCrediticia;
        $nuevoMovimiento->tipo = Movimiento::EGRESO;
        $nuevoMovimiento->empleado()->associate(auth()->user());
        $caja->saldo = $caja->saldo - $parteEfectiva;
        $caja->save();
        $caja->refresh();
        if ($movimientoable instanceof Entrada || $movimientoable instanceof Nomina || $movimientoable instanceof Servicio) {
            $this->actualizarSaldo($movimientoable, $nuevoMovimiento);
            if ($movimientoable->saldo == 0) {
                $movimientoable->fechapagado = now();
            }
            $movimientoable->save();
            $movimientoable->refresh();
        }
        $nuevoMovimiento->caja()->associate($caja);
        $nuevoMovimiento->movimientoable()->associate($movimientoable);
        $nuevoMovimiento->save();
    }

    /**
     * Genera un cobro y su movimiento asociado
     * @param Caja $caja
     * @param $movimientoable
     * @param int $parteEfectiva
     * @param int $parteCrediticia
     */
    public function cobrar(Caja $caja, $movimientoable, $efectivoRecibido = 0, $parteEfectiva = 0, $parteCrediticia = 0)
    {
        $nuevoMovimiento = new Movimiento();
        $nuevoMovimiento->parteEfectiva = $parteEfectiva == null ? 0 : $parteEfectiva;
        $nuevoMovimiento->parteCrediticia = $parteCrediticia == null ? 0 : $parteCrediticia;
        $nuevoMovimiento->efectivoRecibido = $efectivoRecibido == null ? 0 : $efectivoRecibido;
        $nuevoMovimiento->total = $nuevoMovimiento->parteEfectiva + $nuevoMovimiento->parteCrediticia;
        $nuevoMovimiento->cambio = $this->generarCambio($nuevoMovimiento->parteEfectiva, $nuevoMovimiento->efectivoRecibido);
        $nuevoMovimiento->tipo = Movimiento::INGRESO;
        $nuevoMovimiento->empleado()->associate(auth()->user());
        $caja->saldo = $caja->saldo + $parteEfectiva;
        $caja->save();
        $caja->refresh();
        if ($movimientoable instanceof Venta) {
            $this->actualizarSaldo($movimientoable, $nuevoMovimiento);
            if ($movimientoable->saldo == 0) {
                $movimientoable->fechapagado = now();
            };
            $movimientoable->save();
            $movimientoable->refresh();
        }
        $nuevoMovimiento->caja()->associate($caja);
        $nuevoMovimiento->movimientoable()->associate($movimientoable);
        $nuevoMovimiento->save();
        $nuevoMovimiento->refresh();
        return $nuevoMovimiento;
    }

    /**
     * Anula un cobro basado en el movimiento previo, genera un nuevo movimiento
     * @param Caja $caja
     * @param $movimientoable
     * @param $parteEfectiva
     * @param $parteCrediticia
     */
    public function anularCobro($movimiento, $parteEfectiva = null, $parteCrediticia = null)
    {
        $movimientoable = $movimiento->movimientoable;
        $caja = $movimiento->caja->refresh();
        $nuevoMovimiento = new Movimiento();
        $nuevoMovimiento->parteEfectiva = $parteEfectiva == null ? $movimiento->parteEfectiva : $parteEfectiva;
        $nuevoMovimiento->parteCrediticia = $parteCrediticia == null ? $movimiento->parteCrediticia : $parteCrediticia;
        $nuevoMovimiento->total = $nuevoMovimiento->parteEfectiva + $nuevoMovimiento->parteCrediticia;
        $nuevoMovimiento->tipo = Movimiento::EGRESO;
        $nuevoMovimiento->empleado()->associate(auth()->user());
        $caja->saldo = $caja->saldo - $nuevoMovimiento->parteEfectiva;
        $caja->save();
        $caja->refresh();
        if ($movimientoable instanceof Venta) {
            if ($movimientoable->saldo == 0) {
                $movimientoable->fechapagado = null;
            };
            $this->actualizarSaldo($movimientoable, $nuevoMovimiento);
            $movimientoable->save();
            $movimientoable->refresh();
        }
        $nuevoMovimiento->caja()->associate($caja);
        $nuevoMovimiento->movimientoable()->associate($movimientoable);
        $nuevoMovimiento->save();
        $movimiento->delete();

    }


    /**
     * Actualiza el saldo de una entrada sin guardarlo en BD
     * @param Entrada $entrada
     */

    public function actualizarSaldo($movimientoable, $nuevoMovimiento)
    {
        if ($movimientoable instanceof Venta) {
            if ($nuevoMovimiento->tipo == Movimiento::EGRESO) {
                $movimientoable->saldo += $nuevoMovimiento->parteEfectiva + $nuevoMovimiento->parteCrediticia;
            } else if ($nuevoMovimiento->tipo == Movimiento::INGRESO) {
                $movimientoable->saldo -= $nuevoMovimiento->parteEfectiva + $nuevoMovimiento->parteCrediticia;
            }
        } else if ($movimientoable instanceof Entrada || $movimientoable instanceof Nomina || $movimientoable instanceof Servicio) {
            if ($nuevoMovimiento->tipo == Movimiento::EGRESO) {
                $movimientoable->saldo -= $nuevoMovimiento->parteEfectiva + $nuevoMovimiento->parteCrediticia;
            } else if ($nuevoMovimiento->tipo == Movimiento::INGRESO) {
                $movimientoable->saldo += $nuevoMovimiento->parteEfectiva + $nuevoMovimiento->parteCrediticia;
            }
        }
        $movimientoable->abonado = $movimientoable->valor - $movimientoable->saldo;
    }

    public function anularTodosLosPagos($movimientoable)
    {
        foreach ($movimientoable->movimientos as $movimiento) {
            if ($movimiento->tipo == Movimiento::EGRESO)
                $this->anularPago($movimiento);
        }
    }

    public function anularTodosLosCobros($movimientoable)
    {
        foreach ($movimientoable->movimientos as $movimiento) {
            if ($movimiento->tipo == Movimiento::INGRESO)
                $this->anularCobro($movimiento);
        }
    }

    public function getCobroNoAnulable($movimientoable)
    {
        foreach ($movimientoable->movimientos as $movimiento) {
            if ($movimiento->tipo == Movimiento::INGRESO && !$this->isPagable($movimiento->caja, $movimiento->parteEfectiva)) {
                return "el cobro # " . $movimiento->id . " por un monto de " . $movimiento->parteEfectiva;
            }
        }
        return null;
    }

    /**
     * Anula un pago basado en el movimiento previo, genera un nuevo movimiento
     * @param Caja $caja
     * @param $movimientoable
     * @param $parteEfectiva
     * @param $parteCrediticia
     */
    public function anularPago($movimiento, $parteEfectiva = null, $parteCrediticia = null)
    {
        $movimientoable = $movimiento->movimientoable;
        $caja = $movimiento->caja->refresh();
        $nuevoMovimiento = new Movimiento();
        $nuevoMovimiento->parteEfectiva = $parteEfectiva == null ? $movimiento->parteEfectiva : $parteEfectiva;
        $nuevoMovimiento->parteCrediticia = $parteCrediticia == null ? $movimiento->parteCrediticia : $parteCrediticia;
        $nuevoMovimiento->total = $nuevoMovimiento->parteEfectiva + $nuevoMovimiento->parteCrediticia;
        $nuevoMovimiento->tipo = Movimiento::INGRESO;
        $nuevoMovimiento->empleado()->associate(auth()->user());
        $caja->saldo = $caja->saldo + $nuevoMovimiento->parteEfectiva;
        $caja->save();
        $caja->refresh();
        if ($movimientoable instanceof Entrada || $movimientoable instanceof Nomina || $movimientoable instanceof Servicio) {
            if ($movimientoable->saldo == 0) {
                $movimientoable->fechapagado = null;
            };
            $this->actualizarSaldo($movimientoable, $nuevoMovimiento);
            $movimientoable->save();
            $movimientoable->refresh();
        }
        $nuevoMovimiento->caja()->associate($caja);
        $nuevoMovimiento->movimientoable()->associate($movimientoable);
        $nuevoMovimiento->save();
        $movimiento->delete();

    }

    public function isPagable($caja, $parteEfectiva)
    {
        return $parteEfectiva <= $caja->saldo;
    }

    public function generarCierre(Caja $caja)
    {
        $nuevoCierre = new Cierre();
        $nuevoCierre->caja()->associate($caja);
        $nuevoCierre->cierreAnterior()->associate(Cierre::latest()->first());
        $nuevoCierre->save();
    }

    public function isProcesable($movimientoable)
    {
        return $movimientoable->fechapagado == null;
    }

    public function generarCambio($parteEfectiva, $efectivoRecibido)
    {
        return $efectivoRecibido - $parteEfectiva;
    }
}

?>
