<?php

namespace Ism;

use Exception;
use mysqli;
use Random\Randomizer;

class DB {
    static private mysqli $link;
    private const total = 100;

    static public function init(): void {
        $config = new Config();
        self::$link = new mysqli($config->get_db_host(), $config->get_db_user(), $config->get_db_pass(), $config->get_db_name());
    }

    static public function destroy(): void {
        self::$link->close();
    }

    static private function escape_all(&...$params): void {
        foreach ($params as &$param) {
            $param = self::$link->real_escape_string(strip_tags($param));
        }
    }

    static public function get_users_with_transactions(): array {
        $result = self::$link->query("SELECT DISTINCT `u`.`id`, `u`.`name` FROM `users` `u`
            LEFT JOIN `user_accounts` `ua` ON `u`.`id` = `ua`.`user_id`
            LEFT JOIN `transactions` `t` ON `ua`.`id` = `t`.`account_from` OR `ua`.`id` = `t`.`account_to`
            WHERE `t`.`id` IS NOT NULL");
        if ($result && $result->num_rows) {
            return $result->fetch_all(MYSQLI_ASSOC);
        }
        return [];
    }

    static public function get_account_debit($user_id): array {
        self::escape_all($user_id);
        $result = self::$link->query("SELECT SUM(`amount`) AS `Sum`, DATE_FORMAT(`trdate`, '%m.%Y') AS `Trmonth` 
            FROM `user_accounts` `ua`
            LEFT JOIN `transactions` `t` ON `t`.`account_to` = `ua`.`id`
            WHERE `user_id` = $user_id AND `t`.`id` IS NOT NULL
            GROUP BY `Trmonth`");
        if ($result && $result->num_rows) {
            return $result->fetch_all(MYSQLI_ASSOC);
        }
        return [];
    }

    static public function get_account_credit($user_id): array {
        self::escape_all($user_id);
        $result = self::$link->query("SELECT SUM(`amount`) AS `Sum`, DATE_FORMAT(`trdate`, '%m.%Y') AS `Trmonth` 
            FROM `user_accounts` `ua`
            LEFT JOIN `transactions` `t` ON `t`.`account_from` = `ua`.`id`
            WHERE `user_id` = $user_id AND `t`.`id` IS NOT NULL
            GROUP BY `Trmonth`");
        if ($result && $result->num_rows) {
            return $result->fetch_all(MYSQLI_ASSOC);
        }
        return [];
    }
}