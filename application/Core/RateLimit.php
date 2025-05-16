<?php

namespace Highway\Core;

class RateLimit
{
    public function protect()
    {
        try {
            $redis = new \Redis();
            $redis->connect('127.0.0.1', 6379);

            $ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
            $key = "rate_limit:$ip";
            $limit = 10;
            $ttl = 60;

            $requests = $redis->get($key);

            if ($requests !== false && $requests >= $limit) {
                http_response_code(429);
                die("Too many requests. Please wait.");
            }

            $redis->multi();
            $redis->incr($key);
            if ($requests === false) {
                $redis->expire($key, $ttl);
            }
            $redis->exec();
        } catch (\Exception $e) {
            http_response_code(500);
            echo "Erro ao conectar ao Redis: " . $e->getMessage();
            exit;
        }
    }
}
