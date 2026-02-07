<?php

namespace QuickBooksOnlineHelper;

class QuickBooksOnline
{
    /**
     * Fetch all entities
     */
    public function findAll($entity, $companyId, $accessToken, $filter = "", $version = 75, $sandbox = false)
    {
        $endpoint = $sandbox
            ? "https://sandbox-quickbooks.api.intuit.com/v3/company"
            : "https://quickbooks.api.intuit.com/v3/company";

        $stmt = $filter == "" ? "" : " $filter";
        $query = urlencode("select * from $entity" . $stmt);
        $url = "$endpoint/$companyId/query?query=$query&minorversion=$version";

        return $this->makeRequest("GET", $url, $accessToken);
    }

    /**
     * Fetch entity by ID
     */
    public function findById($entity, $id, $companyId, $accessToken, $version = 75, $sandbox = false)
    {
        $endpoint = $sandbox
            ? "https://sandbox-quickbooks.api.intuit.com/v3/company"
            : "https://quickbooks.api.intuit.com/v3/company";

        $entity = strtolower($entity);
        $url = "$endpoint/$companyId/$entity/$id?minorversion=$version";
        return $this->makeRequest("GET", $url, $accessToken);
    }

    /**
     * Create new entity
     */
    public function create($entity, $companyId, $accessToken, $data, $includes = "", $version = 75, $sandbox = false)
    {
        $endpoint = $sandbox
            ? "https://sandbox-quickbooks.api.intuit.com/v3/company"
            : "https://quickbooks.api.intuit.com/v3/company";

        $entity = strtolower($entity);
        $url = "$endpoint/$companyId/$entity?minorversion=$version$includes";
        return $this->makeRequest("POST", $url, $accessToken, $data);
    }

    /**
     * Update entity
     */
    public function update($entity, $companyId, $accessToken, $data, $includes = "", $version = 75, $sandbox = false)
    {
        $endpoint = $sandbox
            ? "https://sandbox-quickbooks.api.intuit.com/v3/company"
            : "https://quickbooks.api.intuit.com/v3/company";

        $entity = strtolower($entity);
        $url = "$endpoint/$companyId/$entity?minorversion=$version$includes";
        return $this->makeRequest("POST", $url, $accessToken, $data);
    }

    /**
     * Delete entity
     */
    public function delete($entity, $id, $syncToken, $companyId, $accessToken, $version = 75, $sandbox = false)
    {
        $endpoint = $sandbox
            ? "https://sandbox-quickbooks.api.intuit.com/v3/company"
            : "https://quickbooks.api.intuit.com/v3/company";

        $entity = strtolower($entity);
        $url = "$endpoint/$companyId/$entity?operation=delete&minorversion=$version";
        $data = ["Id" => $id, "SyncToken" => $syncToken];

        return $this->makeRequest("POST", $url, $accessToken, $data);
    }

    /**
     * Run raw query
     */
    // public function query($query, $companyId, $accessToken, $filter = "", $version = 75, $sandbox = false)
    // {
    //     $endpoint = $sandbox
    //         ? "https://sandbox-quickbooks.api.intuit.com/v3/company"
    //         : "https://quickbooks.api.intuit.com/v3/company";

    //     $stmt = $filter == "" ? "" : " $filter";
    //     $query = urlencode($query . $stmt);
    //     $url = "$endpoint/$companyId/query?query=$query&minorversion=$version";

    //     return $this->makeRequest("GET", $url, $accessToken);
    // }


    public function query($query, $companyId, $accessToken, $version = 75, $sandbox = false)
    {
        $endpoint = $sandbox
            ? "https://sandbox-quickbooks.api.intuit.com/v3/company"
            : "https://quickbooks.api.intuit.com/v3/company";

        $query = urlencode($query);
        $url = "$endpoint/$companyId/query?query=$query&minorversion=$version";

        return $this->makeRequest("GET", $url, $accessToken);
    }

    /**
     * Generic cURL request
     */
    private function makeRequest($method, $url, $accessToken, $data = null)
    {
        $headers = [
            "Accept: application/json",
            "Authorization: Bearer $accessToken",
            "Content-Type: application/json"
        ];

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        if ($method === "POST") {
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        }

        $response = curl_exec($ch);
        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        $decoded = json_decode($response, true);
        return ["status" => $httpcode, "data" => $decoded ?: $response];
    }
}
