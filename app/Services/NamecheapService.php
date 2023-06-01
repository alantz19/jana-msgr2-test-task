<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class NamecheapService
{
    public function __construct(public User $user)
    {
        if (empty($this->user->namecheap)) {
            throw new \Exception('User does not have namecheap credentials');
        }
    }

    public function getTldList()
    {
        $command = 'namecheap.domains.getTldList';

        if ($cached = Cache::get($command)) {
            return json_decode($cached, true);
        }

        $url = $this->buildUrl($command);
        $response = Http::get($url);
        $xml = new \SimpleXMLElement($response->body());
        $errors = $this->getErrors($xml);
        $tlds = [];

        if (empty($errors)) {
            foreach ($xml->CommandResponse->Tlds->Tld as $tld) {
                if ($tld['IsApiRegisterable'] == 'false') {
                    continue;
                }

                $tlds[] = [
                    'name' => (string)$tld['Name'],
                    'min_years' => (int)$tld['MinRegisterYears'],
                    'max_years' => (int)$tld['MaxRegisterYears'],
                ];
            }
        }

        $result = [
            'errors' => $errors,
            'tlds' => $tlds,
        ];

        Cache::set($command, json_encode($result), 60 * 60 * 24);

        return $result;
    }

    public function createDomain($domainName, $years = 1)
    {
        $data = $this->user->namecheap;
        $firstName = $data['first_name'] ?? $this->user->name;
        $lastName = $data['last_name'] ?? null;
        $address1 = $data['address1'] ?? null;
        $address2 = $data['address2'] ?? null;
        $city = $data['city'] ?? null;
        $stateProvince = $data['state_province'] ?? null;
        $postalCode = $data['postal_code'] ?? null;
        $country = $data['country'] ?? null;
        $phone = $data['phone'] ?? null;
        $email = $data['email'] ?? $this->user->email;

        $params = [
            'DomainName' => $domainName,
            'Years' => $years,
            'RegistrantFirstName' => $firstName,
            'RegistrantLastName' => $lastName,
            'RegistrantAddress1' => $address1,
            'RegistrantAddress2' => $address2,
            'RegistrantCity' => $city,
            'RegistrantStateProvince' => $stateProvince,
            'RegistrantPostalCode' => $postalCode,
            'RegistrantCountry' => $country,
            'RegistrantPhone' => $phone,
            'RegistrantEmailAddress' => $email,
            'TechFirstName' => $data['tech_first_name'] ?? $firstName,
            'TechLastName' => $data['tech_last_name'] ?? $lastName,
            'TechAddress1' => $data['tech_address1'] ?? $address1,
            'TechAddress2' => $data['tech_address2'] ?? $address2,
            'TechCity' => $data['tech_city'] ?? $city,
            'TechStateProvince' => $data['tech_state_province'] ?? $stateProvince,
            'TechPostalCode' => $data['tech_postal_code'] ?? $postalCode,
            'TechCountry' => $data['tech_country'] ?? $country,
            'TechPhone' => $data['tech_phone'] ?? $phone,
            'TechEmailAddress' => $data['tech_email_address'] ?? $email,
            'AdminFirstName' => $data['admin_first_name'] ?? $firstName,
            'AdminLastName' => $data['admin_last_name'] ?? $lastName,
            'AdminAddress1' => $data['admin_address1'] ?? $address1,
            'AdminAddress2' => $data['admin_address2'] ?? $address2,
            'AdminCity' => $data['admin_city'] ?? $city,
            'AdminStateProvince' => $data['admin_state_province'] ?? $stateProvince,
            'AdminPostalCode' => $data['admin_postal_code'] ?? $postalCode,
            'AdminCountry' => $data['admin_country'] ?? $country,
            'AdminPhone' => $data['admin_phone'] ?? $phone,
            'AdminEmailAddress' => $data['admin_email_address'] ?? $email,
            'AuxBillingFirstName' => $data['aux_billing_first_name'] ?? $firstName,
            'AuxBillingLastName' => $data['aux_billing_last_name'] ?? $lastName,
            'AuxBillingAddress1' => $data['aux_billing_address1'] ?? $address1,
            'AuxBillingAddress2' => $data['aux_billing_address2'] ?? $address2,
            'AuxBillingCity' => $data['aux_billing_city'] ?? $city,
            'AuxBillingStateProvince' => $data['aux_billing_state_province'] ?? $stateProvince,
            'AuxBillingPostalCode' => $data['aux_billing_postal_code'] ?? $postalCode,
            'AuxBillingCountry' => $data['aux_billing_country'] ?? $country,
            'AuxBillingPhone' => $data['aux_billing_phone'] ?? $phone,
            'AuxBillingEmailAddress' => $data['aux_billing_email_address'] ?? $email,
        ];
        $url = $this->buildUrl('namecheap.domains.create', $params);

        $response = Http::post($url);
        $xml = new \SimpleXMLElement($response->body());
        $errors = $this->getErrors($xml);
        $result = null;

        if (empty($errors)) {
            $result = [
                'domain' => (string)$xml->CommandResponse->DomainCreateResult->attributes()['Domain'],
                'charged_amount' => (string)$xml->CommandResponse->DomainCreateResult->attributes()['ChargedAmount'],
                'domain_id' => (string)$xml->CommandResponse->DomainCreateResult->attributes()['DomainID'],
                'transaction_id' => (string)$xml->CommandResponse->DomainCreateResult->attributes()['TransactionID'],
                'order_id' => (string)$xml->CommandResponse->DomainCreateResult->attributes()['OrderID'],
            ];
        }

        return [
            'errors' => $errors,
            'result' => $result,
        ];
    }

    private function getErrors($xml): array
    {
        $errors = [];
        foreach ($xml->Errors->Error as $error) {
            $errors[] = [
                'number' => (string)$error['Number'],
                'message' => (string)$error,
            ];
        }
        return $errors;
    }

    private function buildUrl(string $command, array $extraParams = []): string
    {
        $authParams = [
            'ApiUser' => $this->user->namecheap['api_user'],
            'ApiKey' => $this->user->namecheap['api_key'],
            'UserName' => $this->user->namecheap['api_user'],
            'ClientIp' => $this->user->namecheap['client_ip'],
        ];

        return config('services.namecheap.api_url') . '?' . http_build_query(
                array_merge(
                    $authParams,
                    ['Command' => $command],
                    $extraParams,
                )
            );
    }
}
