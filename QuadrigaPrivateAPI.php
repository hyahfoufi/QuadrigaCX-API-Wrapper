<?php
/**
 * QuadrigaPrivateAPI class is a wrapper for the QuadrigaCX v2 private API
 * Official API Documentation: https://www.quadrigacx.com/api_info
 */

class QuadrigaPrivateAPI
{
    // Credentials
    private $api_key;
    private $api_secret;
    private $client_id;

    /**
     * QuadrigaPrivateAPI constructor.
     *
     * @param string $api_key
     * @param string $api_secret
     * @param int $client_id
     */
    public function __construct(string $api_key, string $api_secret, int $client_id)
    {
        $this->api_key = $api_key;
        $this->api_secret = $api_secret;
        $this->client_id = $client_id;
    }

    /**
     * Main method to send an API request
     *
     * @param $endpoint
     * @param array $params parameters to be POSTed
     * @return json dictionary with API response
     */
    private function _private_api($endpoint, $params = array()) {
        $nonce = time(); // Unix timestamp

        // Signature hash
        $signature = hash_hmac(
            'sha256',
            $nonce . $this->client_id . $this->api_key,
            $this->api_secret
        );

        // Build payload
        $payload = array(
            'key'       => $this->api_key,
            'nonce'     => $nonce,
            'signature' => $signature
        );

        // Add additional parameters to payload
        foreach ($params as $key => $value) {
            $payload[$key] = $value;
        }

        $payload_json = json_encode($payload);

        $ch = curl_init('https://api.quadrigacx.com/v2/' . $endpoint);

        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload_json);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json; charset=utf-8',
                'Content-Length: ' . strlen($payload_json))
        );
        return curl_exec($ch);
    }

    /**
     * Get account balances
     *
     * @return json dictionary with all account balances
     */
    public function balance() {
        return $this->_private_api('balance');
    }

    /**
     * List of user transactions
     *
     * @param int $offset skips that many transactions before beginning to return results
     * @param int $limit limits the result to that many transactions
     * @param string $sort sort by date and time (asc - ascending; desc - descending)
     * @param string $book trading pair
     * @return json dictionary with user transactions
     */
    public function user_transactions(int $offset = null, int $limit = null, string $sort = null, string $book = null) {
        $endpoint = "user_transactions";
        $options = get_defined_vars();
        $params = array();
        foreach ($options as $key => $item) {
            if (isset($key)) {
                $params[$key] = $item;
            }
        }
        return $this->_private_api($endpoint, $params);
    }

    /**
     * List of open orders
     *
     * @param string $book trading pair
     * @return json dictionary with open orders
     */
    public function open_orders(string $book = null) {
        $endpoint = "open_orders";
        $params = array();
        if ($book != null) {
            $params['book'] = $book;
        }
        return $this->_private_api($endpoint, $params);
    }

    /**
     * List of details about 1 or more orders
     *
     * @param string|array $id a single order id or an array of order id's
     * @return json dictionary with information pertaining to the order id(s) given
     */
    public function lookup_order($id) {
        $endpoint = "lookup_order";
        $params['id'] = array();
        // Detect if $id is a single id or an array of id's, and set the $params to be POSTed
        if (is_array($id)) {
            foreach ($id as $value) {
                array_push($params['id'], $value);
            }
        } else {
            $params['id'] = $id;
        }

        return $this->_private_api($endpoint, $params);
    }

    /**
     * Cancel an order
     *
     * @param string $id with the order id to be cancelled
     * @return bool 'true' if order has been found and cancelled
     */
    public function cancel_order(string $id) {
        $endpoint = "cancel_order";
        $params['id'] = $id;
        return $this->_private_api($endpoint, $params);
    }

    /**
     * Place a buy or sell limit order
     *
     * @param string $direction (buy; sell)
     * @param float $amount amount to order
     * @param float $price price to order at
     * @param string $book trading pair
     * @return json dictionary with order information
     */
    public function limit_order(string $direction, float $amount, float $price, string $book = null) {
        if ($direction == "buy")
            $endpoint = "buy";
        else if ($direction == "sell")
            $endpoint = "sell";
        $params['amount'] = $amount;
        $params['price'] = $price;
        if ($book != null)
            $params['book'] = $book;
        return $this->_private_api($endpoint, $params);
    }

    /**
     * Buy or sell at market
     *
     * @param string $direction (buy; sell)
     * @param float $amount amount to buy or sell
     * @param string $book trading pair
     * @return json dictionary with order information
     */
    public function buy_market(string $direction, float $amount, string $book = null) {
        if ($direction == "buy")
            $endpoint = "buy";
        else if ($direction == "sell")
            $endpoint = "sell";

        $params['amount'] = $amount;
        if ($book != null)
            $params['book'] = $book;
        return $this->_private_api($endpoint, $params);
    }

    /**
     * Retrieve deposit address
     *
     * @param string $currency currency code
     * @return bool|json json dictionary with deposit address, false on error
     */
    public function deposit(string $currency) {
        switch ($currency) {
            case "btc":
                $endpoint = "bitcoin_deposit_address";
                break;
            case "bch":
                $endpoint = "bitcoincash_deposit_address";
                break;
            case "btg":
                $endpoint = "bitcoingold_deposit_address";
                break;
            case "ltc":
                $endpoint = "litecoin_deposit_address";
                break;
            case "eth":
                $endpoint = "ether_deposit_address";
                break;
            default:
                return false;
        }

        return $this->_private_api($endpoint);
    }

    /**
     * Withdraw currency to given address
     *
     * @param string $currency currency code
     * @param float $amount amount to withdraw
     * @param string $address wallet address to send to
     * @return bool|json OK or error
     */
    public function withdraw(string $currency, float $amount, string $address) {
        switch ($currency) {
            case "btc":
                $endpoint = "bitcoin_withdrawal";
                break;
            case "bch":
                $endpoint = "bitcoincash_withdrawal";
                break;
            case "btg":
                $endpoint = "bitcoingold_withdrawal";
                break;
            case "ltc":
                $endpoint = "litecoin_withdrawal";
                break;
            case "eth":
                $endpoint = "ether_withdrawal";
                break;
            default:
                return false;
        }
        $params['amount'] = $amount;
        $params['address'] = $address;

        return $this->_private_api($endpoint, $params);
    }
}