<?php

return [
    'default_vat_percent' => (float) env('DEFAULT_VAT_PERCENT', 5),

    'accounts' => [
        'inventory' => 'Inventory',
        'capital' => 'Capital',
        'accounts_receivable' => 'Accounts Receivable',
        'sales_revenue' => 'Sales Revenue',
        'vat_payable' => 'VAT Payable',
        'cost_of_goods_sold' => 'Cost of Goods Sold',
        'cash' => 'Cash',
    ],
];
