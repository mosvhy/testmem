<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TestController extends Controller
{
    public function index()
    {
        $result = [];
        $start = 0;
        $limit = 5000;
        
        $total_data_1 = DB::selectOne("SELECT COUNT(*) as total FROM test_t1;", [])->total;
        $total_data_2 = DB::selectOne("SELECT COUNT(*) as total FROM test_t2;", [])->total;
        $total_data_3 = DB::selectOne("SELECT COUNT(*) as total FROM test_t3;", [])->total;

        // $higher_total = max($total_data_1, $total_data_2, $total_data_3);
        $higher_total = 10000;

        $mergedData = [];
        $data_1 = [];
        $data_2 = [];
        $data_3 = [];


        for ($i = $start; $i < $higher_total; $i += $limit) {
            if ($i < $total_data_1) {
                $data_1[] = DB::select("
                SELECT
                    t1.ref_no as t1_ref_no,
                    t1.vend_ref_no as t1_vend_ref_no,
                    t1.payment_method as t1_payment_method,
                    t1.amount as t1_amount,
                    t1.fee as t1_fee,
                    t1.total_amount as t1_total_amount,
                    t1.status as t1_status,
                    t1.created_at as t1_created_at,
                    t1.paid_at as t1_paid_at
                FROM test_t1 t1
                LIMIT $i, $limit;", []);
            }
            if ($i < $total_data_2) {
                $data_2[] = DB::select("SELECT
                    t2.tx_ref_no as t2_ref_no,
                    t2.tx_vend_ref_no as t2_vend_ref_no,
                    t2.payment_method as t2_payment_method,
                    t2.amount as t2_amount,
                    t2.fee as t2_fee,
                    t2.total_amount as t2_total_amount,
                    t2.status as t2_status,
                    t2.created_at as t2_created_at,
                    t2.paid_at as t2_paid_at
                FROM test_t2 t2
                LIMIT $i, $limit;", []);
            }
            if ($i < $total_data_3) {
                $data_3[] = DB::select("SELECT
                    t3.tx_ref_no as t3_ref_no,
                    t3.tx_vend_ref_no as t3_vend_ref_no,
                    t3.payment_method as t3_payment_method,
                    t3.amount as t3_amount,
                    t3.fee as t3_fee,
                    t3.total_amount as t3_total_amount,
                    t3.status as t3_status,
                    t3.created_at as t3_created_at,
                    t3.paid_at as t3_paid_at
                FROM test_t3 t3
                LIMIT $i, $limit;", []);
            }
        }

        $data_1 = array_merge(...$data_1);
        $data_2 = array_merge(...$data_2);
        $data_3 = array_merge(...$data_3);

        $allData = array_merge($data_1, $data_2, $data_3);

        foreach ($allData as $item) {
            $key = $item->t1_ref_no ?? $item->t2_ref_no ?? $item->t3_ref_no;
            $vend_key = $item->t1_vend_ref_no ?? $item->t2_vend_ref_no ?? $item->t3_vend_ref_no;
            $uniqueKey = $key . $vend_key;

            if (!isset($mergedData[$uniqueKey])) {
                $mergedData[$uniqueKey] = (object) [
                    't1_ref_no' => $item->t1_ref_no ?? null,
                    't1_vend_ref_no' => $item->t1_vend_ref_no ?? null,
                    't1_payment_method' => $item->t1_payment_method ?? null,
                    't1_amount' => $item->t1_amount ?? null,
                    't1_fee' => $item->t1_fee ?? null,
                    't1_total_amount' => $item->t1_total_amount ?? null,
                    't1_status' => $item->t1_status ?? null,
                    't1_created_at' => $item->t1_created_at ?? null,
                    't1_paid_at' => $item->t1_paid_at ?? null,
                    't2_ref_no' => $item->t2_ref_no ?? null,
                    't2_vend_ref_no' => $item->t2_vend_ref_no ?? null,
                    't2_payment_method' => $item->t2_payment_method ?? null,
                    't2_amount' => $item->t2_amount ?? null,
                    't2_fee' => $item->t2_fee ?? null,
                    't2_total_amount' => $item->t2_total_amount ?? null,
                    't2_status' => $item->t2_status ?? null,
                    't2_created_at' => $item->t2_created_at ?? null,
                    't2_paid_at' => $item->t2_paid_at ?? null,
                    't3_ref_no' => $item->t3_ref_no ?? null,
                    't3_vend_ref_no' => $item->t3_vend_ref_no ?? null,
                    't3_payment_method' => $item->t3_payment_method ?? null,
                    't3_amount' => $item->t3_amount ?? null,
                    't3_fee' => $item->t3_fee ?? null,
                    't3_total_amount' => $item->t3_total_amount ?? null,
                    't3_status' => $item->t3_status ?? null,
                    't3_created_at' => $item->t3_created_at ?? null,
                    't3_paid_at' => $item->t3_paid_at ?? null,
                ];
                } else {
                if (isset($item->t1_ref_no)) {
                    $mergedData[$uniqueKey]->t1_ref_no = $item->t1_ref_no;
                    $mergedData[$uniqueKey]->t1_vend_ref_no = $item->t1_vend_ref_no;
                    $mergedData[$uniqueKey]->t1_payment_method = $item->t1_payment_method;
                    $mergedData[$uniqueKey]->t1_amount = $item->t1_amount;
                    $mergedData[$uniqueKey]->t1_fee = $item->t1_fee;
                    $mergedData[$uniqueKey]->t1_total_amount = $item->t1_total_amount;
                    $mergedData[$uniqueKey]->t1_status = $item->t1_status;
                    $mergedData[$uniqueKey]->t1_created_at = $item->t1_created_at;
                    $mergedData[$uniqueKey]->t1_paid_at = $item->t1_paid_at;
                }
                if (isset($item->t2_ref_no)) {
                    $mergedData[$uniqueKey]->t2_ref_no = $item->t2_ref_no;
                    $mergedData[$uniqueKey]->t2_vend_ref_no = $item->t2_vend_ref_no;
                    $mergedData[$uniqueKey]->t2_payment_method = $item->t2_payment_method;
                    $mergedData[$uniqueKey]->t2_amount = $item->t2_amount;
                    $mergedData[$uniqueKey]->t2_fee = $item->t2_fee;
                    $mergedData[$uniqueKey]->t2_total_amount = $item->t2_total_amount;
                    $mergedData[$uniqueKey]->t2_status = $item->t2_status;
                    $mergedData[$uniqueKey]->t2_created_at = $item->t2_created_at;
                    $mergedData[$uniqueKey]->t2_paid_at = $item->t2_paid_at;
                }
                if (isset($item->t3_ref_no)) {
                    $mergedData[$uniqueKey]->t3_ref_no = $item->t3_ref_no;
                    $mergedData[$uniqueKey]->t3_vend_ref_no = $item->t3_vend_ref_no;
                    $mergedData[$uniqueKey]->t3_payment_method = $item->t3_payment_method;
                    $mergedData[$uniqueKey]->t3_amount = $item->t3_amount;
                    $mergedData[$uniqueKey]->t3_fee = $item->t3_fee;
                    $mergedData[$uniqueKey]->t3_total_amount = $item->t3_total_amount;
                    $mergedData[$uniqueKey]->t3_status = $item->t3_status;
                    $mergedData[$uniqueKey]->t3_created_at = $item->t3_created_at;
                    $mergedData[$uniqueKey]->t3_paid_at = $item->t3_paid_at;
                }
            }
        }
        
        $result['total_data_1'] = $total_data_1;
        $result['total_data_2'] = $total_data_2;
        $result['total_data_3'] = $total_data_3;

        $result['meta_data'] = null;
        $result['merged_data'] = array_map(function($row) use (&$result) {
            $row_array = get_object_vars($row);
            if (is_null($result['meta_data'])) {
                $result['meta_data'] = array_keys($row_array);
            }
            return array_values($row_array);
        }, array_values($mergedData));

        return response()->json($result);
    }
}
