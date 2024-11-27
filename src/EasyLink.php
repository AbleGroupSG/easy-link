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

    /**
     * @return array{
     *      code: int,
     *      message: string,
     *     data: array<int, array{
     *         country_code: string,
     *         country_name: string,
     *         alpha2_code: string,
     *         alpha3_code: string,
     *         country_flag_url: string,
     *     }>
     *  }
     * @throws ConnectionException
     * @throws Exception
     */
    public function countriesList(): array
    {
        return Http::withHeaders($this->getHeader())
            ->post(config('easy-link.url') . '/data/country-info')
            ->json();
    }

    /**
     * @param array{
     *      destination_country: string,
     *      beneficiary_account_type: string,
     *      segment: string
     * }$payload
     * @return array{
     *      code: int,
     *      message: string,
     *      data: array<int, array{
     *         purpose: string,
     *         purpose_code: string,
     *     }>
     *  }
     * @throws ConnectionException
     * @throws Exception
     */
    public function remittancePurposesList(array $payload): array
    {
        $payload = [
            'destination_country' => $payload['destination_country'],
            'beneficiary_account_type' => $payload['beneficiary_account_type'],
            'segment' => $payload['segment'],
        ];
        return Http::withHeaders($this->getHeader($payload))
            ->post(config('easy-link.url') . '/data/get-remittance-purposes', $payload)
            ->json();
    }

    /**
     * @return array{
     *      code: int,
     *      message: string,
     *     data: array<int, array{
     *         currency_code: string,
     *         currency: string,
     *         country_name: string,
     *         country_alpha2_code: string,
     *         country_alpha3_code: string,
     *         country_flag_url: string,
     *     }>
     *  }
     * @throws ConnectionException
     * @throws Exception
     */
    public function countriesCurrencies(): array
    {
        return Http::withHeaders($this->getHeader())
            ->post(config('easy-link.url') . '/data/countries-currencies')
            ->json();
    }


    /**
     * @param string $reference
     * @return array{
     *      code: string,
     *      message: string,
     *      data: array{
     *          source: array{
     *              segment: string,
     *              company_name: string,
     *              company_trading_name: string,
     *              company_registration_number: string,
     *              company_registration_country: string,
     *              address_line: string,
     *              address_city: string,
     *              address_country: string,
     *          },
     *          destination: array{
     *              segment:string,
     *              beneficiary_account_type:string,
     *              bank:string,
     *              bank_code:string,
     *              bank_account_number:string,
     *              company_name:string,
     *              address_line:string,
     *              address_city:string,
     *              address_country:string,
     *              relation:string,
     *              relation_code:string,
     *              purpose:string,
     *              purpose_code:string,
     *              source_of_income:string,
     *              source_of_income_code:string,
     *          },
     *          transaction: array{
     *              source_country: string,
     *              source_currency: string,
     *              destination_country: string,
     *              destination_currency: string,
     *              source_amount: string,
     *              destination_amount: string,
     *          },
     *          reference: string,
     *          state: int,
     *          reason: string,
     *          rate: string,
     *          fee: string,
     *          fee_currency: string,
     *          created: int,
     *          updated: int,
     *      }
     *  }
     *
     * @throws ConnectionException
     * @throws Exception
     */
    public function getInternationalTransfer(string $reference): array
    {
        return Http::withHeaders($this->getHeader())
            ->get(config('easy-link.url') . '/transfer/get-international-transfer', ['reference' => $reference])
            ->json();
    }


    /**
     * Creates an international transfer using the provided payload.
     *
     * This method sends a POST request to the Easy-Link API to initiate an international transfer.
     *
     * @param array{
     *   "transaction": array{
     *     "destination_country": string,
     *     "destination_currency": string,
     *     "destination_amount": float
     *   },
     *   "source": array{
     *     "segment": string,
     *     "address_country": string,
     *     "address_city": string,
     *     "address_line": string,
     *     "company_name": string,
     *     "company_trading_name": string,
     *     "company_registration_number": string,
     *     "company_registration_country": string
     *   },
     *   "destination": array{
     *     "segment": string,
     *     "email": string,
     *     "swift_code" : string,
     *     "beneficiary_account_type": string,
     *     "bank": string,
     *     "bank_code": string,
     *     "bank_account_number": string,
     *     "company_name": string,
     *     "address_city": string,
     *     "address_country": string,
     *     "address_line": string,
     *     "relation": string,
     *     "relation_code": string,
     *     "purpose": string,
     *     "purpose_code": string,
     *     "source_of_income": string,
     *     "source_of_income_code": string,
     *     "contract_key": string
     *   },
     *   "reference": string
     * }
     * $payload
     * @return array The response from the Easy-Link API.
     * @throws ConnectionException
     * @throws Exception
     */
    public function createInternationalTransfer(array $payload): array
    {
        return Http::withHeaders($this->getHeader($payload))
            ->post(config('easy-link.url') . '/transfer/create-international-transfer', $payload)
            ->json();
    }


}











