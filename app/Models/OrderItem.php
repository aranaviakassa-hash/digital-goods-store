<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use InvalidArgumentException;

class OrderItem extends Model
{
    protected $fillable = [
        'order_id',
        'product_id',
        'product_name',
        'product_code',
        'quantity',
        'unit_price',
        'total_price',
        'currency',
        'delivery_data',
    ];

    protected function casts(): array
    {
        return [
            'quantity' => 'integer',
            'unit_price' => 'decimal:2',
            'total_price' => 'decimal:2',
            'delivery_data' => 'array',
        ];
    }

    protected static function booted(): void
    {
        static::saving(function (OrderItem $orderItem) {
            $quantity = max(
                0,
                (int) $orderItem->quantity
            );

            $unitPriceMinor = self::moneyToMinorUnits(
                (string) $orderItem->unit_price
            );

            $totalMinor =
                $unitPriceMinor * $quantity;

            $orderItem->total_price =
                self::minorUnitsToMoney(
                    $totalMinor
                );
        });

        static::saved(function (OrderItem $orderItem) {
            $orderItem->recalculateOrderTotals();
        });

        static::deleted(function (OrderItem $orderItem) {
            $orderItem->recalculateOrderTotals();
        });
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function recalculateOrderTotals(): void
    {
        $order = $this->order;

        if (! $order) {
            return;
        }

        $total = $order->items()->sum('total_price');

        $order->update([
            'subtotal' => $total,
            'total' => $total,
        ]);
    }

    private static function moneyToMinorUnits(
        string $amount
    ): int {
        $value = trim($amount);

        if (
            ! preg_match(
                '/^\d+(?:\.\d{1,2})?$/',
                $value
            )
        ) {
            throw new InvalidArgumentException(
                'Invalid monetary amount format.'
            );
        }

        [$whole, $fraction] =
            array_pad(
                explode(
                    '.',
                    $value,
                    2
                ),
                2,
                ''
            );

        $fraction = str_pad(
            $fraction,
            2,
            '0',
            STR_PAD_RIGHT
        );

        return ((int) $whole * 100)
            + (int) $fraction;
    }

    private static function minorUnitsToMoney(
        int $minorUnits
    ): string {
        $whole = intdiv(
            $minorUnits,
            100
        );

        $fraction = $minorUnits % 100;

        return sprintf(
            '%d.%02d',
            $whole,
            $fraction
        );
    }
}
