<?php

namespace Ism;

use DateTime;

class Ajax {
    static function resolve($params = null): void {
        $op = $params["op"] ?? "";
        if (method_exists(self::class, $op)) {
            DB::init();
            self::$op($params);
            DB::destroy();
        }
        else {
            http_response_code(404);
        }
    }


    static function get_balance($params): void {
        $debit = DB::get_account_debit($params["user_id"] ?? 0);
        $credit = DB::get_account_credit($params["user_id"] ?? 0);
        $response = [];
        foreach ($debit as $value) {
            $response[$value["Trmonth"]] = $value["Sum"];
        }
        foreach ($credit as $value) {
            if (!isset($response[$value["Trmonth"]])) {
                $response[$value["Trmonth"]] = 0;
            }
            $response[$value["Trmonth"]] -= $value["Sum"];
        }
        uksort($response, fn($a, $b) => DateTime::createFromFormat("m.Y", $a) < DateTime::createFromFormat("m.Y", $b)? 1: -1);
        HTTP::response_success($response);
    }

}