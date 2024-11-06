<?php

namespace Serengiy\EasyLink;

use Exception;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;

class EasyLink extends EasyLinkAbstract
{
    /**
     * @throws ConnectionException
     * @throws Exception
     */
    public function createDomesticTransfer(array $payload): string
    {
        return Http::withHeaders($this->getHeader($payload))
            ->post(config('easy-link.url') . '/v2/transfer/create-domestic-transfer', $payload)
            ->body();
    }

    /**
     * @throws ConnectionException
     * @throws Exception
     */
    public function getDomesticTransfer(string $reference): string
    {
        return Http::withHeaders($this->getHeader())
            ->get(config('easy-link.url') . '/transfer/get-domestic-transfer', ['reference' => $reference])
            ->body();
    }

    /**
     * @throws ConnectionException
     * @throws Exception
     */
    public function balancesList(): string
    {
        return Http::withHeaders($this->getHeader())
            ->post(config('easy-link.url') . '/finance-account/balances')->body();
    }

    /**
     * @throws ConnectionException
     * @throws Exception
     */
    public function verifyBankAccount(array $payload): string
    {
        return Http::withHeaders($this->getHeader($payload))
            ->post(config('easy-link.url') . '/v2/transfer/verify-bank-account', $payload)
            ->body();
    }

    /**
     * @throws ConnectionException
     * @throws Exception
     */
    public function bankList(): string
    {
        return Http::withHeaders($this->getHeader())
            ->post(config('easy-link.url') . '/v2/data/supported-bank-code')
            ->body();
    }
}
