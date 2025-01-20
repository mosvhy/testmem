<?php

// Sample hardcoded data for vendors
$v1 = [
    ['ref_no' => 1001, 'vend_ref_no' => 'A123', 'date' => '2025-01-01', 'amount' => 5000, 'status' => 'Active'],
    ['ref_no' => 1002, 'vend_ref_no' => 'A124', 'date' => '2025-01-02', 'amount' => 6000, 'status' => 'Inactive']
];

$v2 = [
    ['vend_ref_no' => 'A123', 'trans_date' => '2025-01-01', 'trans_amount' => 5000, 'trans_status' => 'Active', 'aggrgate_no' => 'B456'],
    ['vend_ref_no' => 'A124', 'trans_date' => '2025-01-02', 'trans_amount' => 6000, 'trans_status' => 'Inactive', 'aggrgate_no' => 'B457']
];

$v3 = [
    ['aggregate_no' => 'B456', 'transaction_date' => '2025-01-01', 'transaction_amount' => 5000, 'transaction_status' => 'Active', 'trx_no' => 'C789'],
    ['aggregate_no' => 'B457', 'transaction_date' => '2025-01-02', 'transaction_amount' => 6000, 'transaction_status' => 'Inactive', 'trx_no' => 'C790']
];

$v4 = [
    ['trx_id' => 'T1', 'trx_no' => 'C789', 'trx_date' => '2025-01-01', 'trx_amount' => 5000, 'trx_status' => 'Active'],
    ['trx_id' => 'T2', 'trx_no' => 'C790', 'trx_date' => '2025-01-02', 'trx_amount' => 6000, 'trx_status' => 'Inactive']
];

// Define the column mappings between vendors
$columnMappings = [
    'v1' => ['date' => 'date', 'amount' => 'amount', 'status' => 'status'],       // v1 fields
    'v2' => ['date' => 'trans_date', 'amount' => 'trans_amount', 'status' => 'trans_status'], // v2 fields
    'v3' => ['date' => 'transaction_date', 'amount' => 'transaction_amount', 'status' => 'transaction_status'], // v3 fields
    'v4' => ['date' => 'trx_date', 'amount' => 'trx_amount', 'status' => 'trx_status'], // v4 fields
];

// Function to compare v1 and v4 based on their relations through v2 and v3
function compareVendorData($v1, $v2, $v3, $v4, $columnMappings)
{
    // Result array to store comparison results
    $results = [];

    // Loop through v1 data
    foreach ($v1 as $v1Item) {
        // Find matching v2 record based on vend_ref_no
        $v2Item = findInArray($v2, 'vend_ref_no', $v1Item['vend_ref_no']);

        if ($v2Item) {
            // Find matching v3 record based on aggregate_no
            $v3Item = findInArray($v3, 'aggregate_no', $v2Item['aggrgate_no']);

            if ($v3Item) {
                // Find matching v4 record based on trx_no
                $v4Item = findInArray($v4, 'trx_no', $v3Item['trx_no']);

                if ($v4Item) {
                    // Compare fields using column mappings
                    $comparisonResult = compareFields($v1Item, $v4Item, $columnMappings);
                    $results[] = [
                        'v1_ref_no' => $v1Item['ref_no'],
                        'v2_vend_ref_no' => $v2Item['vend_ref_no'],
                        'v3_aggregate_no' => $v3Item['aggregate_no'],
                        'v4_trx_id' => $v4Item['trx_id'],
                        'comparison' => $comparisonResult
                    ];
                } else {
                    // No match found for v4
                    $results[] = [
                        'v1_ref_no' => $v1Item['ref_no'],
                        'v2_vend_ref_no' => $v2Item['vend_ref_no'],
                        'v3_aggregate_no' => $v3Item['aggregate_no'],
                        'v4_trx_id' => null,
                        'comparison' => 'No match found for v4'
                    ];
                }
            } else {
                // No match found for v3
                $results[] = [
                    'v1_ref_no' => $v1Item['ref_no'],
                    'v2_vend_ref_no' => $v2Item['vend_ref_no'],
                    'v3_trx_no' => null,
                    'comparison' => 'No match found for v3'
                ];
            }
        } else {
            // No match found for v2
            $results[] = [
                'v1_ref_no' => $v1Item['ref_no'],
                'v2_aggr_no' => null,
                'comparison' => 'No match found for v2'
            ];
        }
    }

    return $results;
}

// Helper function to find an item in an array by a key-value match
function findInArray($array, $key, $value)
{
    foreach ($array as $item) {
        if (isset($item[$key]) && $item[$key] === $value) {
            return $item;
        }
    }
    return null;
}

// Function to compare relevant fields (date, amount, status) based on column mappings
function compareFields($v1Item, $v4Item, $columnMappings)
{
    $comparison = [];

    // Compare date
    $v1Date = $v1Item[$columnMappings['v1']['date']];
    $v4Date = $v4Item[$columnMappings['v4']['date']];
    $comparison['date_match'] = ($v1Date === $v4Date) ? 'Match' : 'No match';

    // Compare amount
    $v1Amount = $v1Item[$columnMappings['v1']['amount']];
    $v4Amount = $v4Item[$columnMappings['v4']['amount']];
    $comparison['amount_match'] = ($v1Amount === $v4Amount) ? 'Match' : 'No match';

    // Compare status
    $v1Status = $v1Item[$columnMappings['v1']['status']];
    $v4Status = $v4Item[$columnMappings['v4']['status']];
    $comparison['status_match'] = ($v1Status === $v4Status) ? 'Match' : 'No match';

    return $comparison;
}

// Run the comparison
$results = compareVendorData($v1, $v2, $v3, $v4, $columnMappings);

// Output the results
echo "<pre>";
print_r($results);
echo "</pre>";
