<?php

namespace Serengiy\EasyLink;

use Exception;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;

class EasyLink extends EasyLinkAbstract
{
    /**
     * @param array{
     *      reference: string,
     *      bank_id: string,
     *      account_holder_name: string,
     *      account_number: string,
     *      amount: string,
     *      description: string
     *  } $payload The payload data for creating a domestic transfer.
     *
     * @return array{
     *      code: string,
     *      message: string,
     *      data: array{
     *          merchant_id: string,
     *          reference: string,
     *          disbursement_id: string,
     *          fee: string,
     *          amount: string,
     *          total_amount: string,
     *          account_holder_name: string,
     *          account_number: string,
     *          state: int,
     *          bank_id: string,
     *      }
     *  }
     * @throws ConnectionException
     * @throws Exception
     */
    public function createDomesticTransfer(array $payload): array
    {
        return Http::withHeaders($this->getHeader($payload))
            ->post(config('easy-link.url') . '/v2/transfer/create-domestic-transfer', $payload)
            ->json();
    }

    /**
     * @param string $reference
     * @return array{
     *      code: string,
     *      message: string,
     *      data: array{
     *          disbursement_id : string,
     *          merchant_id: string,
     *          reference: string,
     *          remittance_type: string,
     *          source_country: string,
     *          source_currency: string,
     *          destination_country: string,
     *          destination_currency: string,
     *          source_amount: string,
     *          destination_amount: string,
     *          fee: string,
     *          created_at: string,
     *          reason: string,
     *          state: string,
     *      }
     *  }
     *
     * @throws ConnectionException
     * @throws Exception
     */
    public function getDomesticTransfer(string $reference): array
    {
        return Http::withHeaders($this->getHeader())
            ->get(config('easy-link.url') . '/transfer/get-domestic-transfer', ['reference' => $reference])
            ->json();
    }

    /**
     * @return array{
     *     code: string,
     *     message: string,
     *     data: array{
     *         balance: string,
     *     }
     * }
     *
     * @throws Exception
     * @throws ConnectionException
     */
    public function balancesList(): array
    {
        return Http::withHeaders($this->getHeader())
            ->post(config('easy-link.url') . '/finance-account/balances')->json();
    }

    /**
     * @return array{
     *     code: string,
     *     message: string,
     *     data: array{
     *         bank_account_number: string,
     *         bank_account_name: string,
     *     }
     * }
     * @throws ConnectionException
     * @throws Exception
     */
    public function verifyBankAccount(array $payload): array
    {
        return Http::withHeaders($this->getHeader($payload))
            ->post(config('easy-link.url') . '/v2/transfer/verify-bank-account', $payload)
            ->json();
    }

    /**
     * @return array{
     *      code: int,
     *      message: string,
     *     data: array<int, array{
     *         bank_id: string,
     *         bank_name: string
     *     }>
     *  }
     * @throws ConnectionException
     * @throws Exception
     */
    public function bankList(): array
    {
        return Http::withHeaders($this->getHeader())
            ->post(config('easy-link.url') . '/v2/data/supported-bank-code')
            ->json();
    }
}
