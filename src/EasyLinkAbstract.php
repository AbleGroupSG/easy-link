<?php

namespace Serengiy\EasyLink;

use Exception;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

abstract class EasyLinkAbstract
{
    private function getToken()
    {
        $url = config('easy-link.url');
        $res = Http::post($url . '/get-access-token', [
            'app_id'=> config('easy-link.app_id'),
            'app_secret'=> config('easy-link.app_secret')
        ]);
        return $res->json()['data'];
    }

    /**
     * @throws Exception
     */
    protected function getHeader(?array $payload = []):array
    {
        $uuid = (string) Str::uuid();
        $timestamp = now()->timestamp * 1000;

        $signature = $this->generateSignature($payload, $uuid, $timestamp);

        return [
            'Authorization' => 'Bearer '. $this->getToken(),
            'X-EasyLink-AppKey' => config('easy-link.company_key'),
            'X-EasyLink-Nonce' => $uuid,
            'X-EasyLink-Timestamp' => $timestamp,
            'X-EasyLink-Sign' => $signature,
        ];
    }

    /**
     * @throws Exception
     */
    private function generateSignature(array $post, string $uuid, int $time): string
    {
        $post['X-EasyLink-AppKey'] = config('easy-link.company_key');
        $post['X-EasyLink-Nonce'] = $uuid;
        $post['X-EasyLink-Timestamp'] = $time;

        $keys = array_keys($post);
        array_multisort($keys);

        $originStr = '';
        foreach ($keys as $key) {
            $originStr .= ($originStr === '' ? '' : '&') . $key . '=' . urlencode($post[$key]);
        }

        $sign = config('easy-link.company_key') . $originStr . config('easy-link.company_key');

        $keyPath = 'file://' . config('easy-link.private_key');
        $privateKey = openssl_pkey_get_private($keyPath);
        if (!$privateKey) {
            throw new Exception("Unable to load private key");
        }

        if (!openssl_sign($sign, $signature, $privateKey, OPENSSL_ALGO_SHA256)) {
            throw new Exception("Signature generation failed");
        }

        return base64_encode($signature);
    }
}
