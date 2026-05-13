<?php

namespace App\Traits;

trait CalculatesTotals
{
    /**
     * Menghitung semua komponen finansial untuk Invoice atau Receipt.
     *
     * @param array $items
     * @param float|null $taxPercent
     * @param float|null $discountPercent
     * @return array
     */
    public function calculateFinancials(array $items, $taxPercent = 0, $discountPercent = 0): array
    {
        $subtotal = 0;

        foreach ($items as $item) {
            $subtotal += ($item['qty'] ?? 0) * ($item['harga'] ?? 0);
        }

        $taxPercent = (float) ($taxPercent ?? 0);
        $discountPercent = (float) ($discountPercent ?? 0);

        $taxAmount = $subtotal * ($taxPercent / 100);
        $discountAmount = $subtotal * ($discountPercent / 100);
        $total = $subtotal + $taxAmount - $discountAmount;

        return [
            'subtotal' => $subtotal,
            'tax_percent' => $taxPercent,
            'discount_percent' => $discountPercent,
            'total' => $total,
            'tax_amount' => $taxAmount,
            'discount_amount' => $discountAmount
        ];
    }
}
