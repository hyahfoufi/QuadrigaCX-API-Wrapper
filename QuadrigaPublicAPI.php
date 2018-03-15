<?php
/**
 * QuadrigaPublicAPI class is a wrapper for the QuadrigaCX v2 public API
 * Official API Documentation: https://www.quadrigacx.com/api_info
 */

class QuadrigaPublicAPI
{
    /**
     * Main method to send an API request
     *
     * @param string $endpoint with the kind of API call to add to the url
     * @param array $params with the parameters to be passed to the API server
     * @return json dictionary with API response
     */
    private function _public_api($endpoint, $params = array()) {
        $ch = curl_init('https://api.quadrigacx.com/v2/' . $endpoint . '?' . http_build_query($params));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, false);

        return curl_exec($ch);
    }

    /**
     * Retrieves trading information of a given book
     *
     * @param string $book with trading pair
     * @return json dictionary with trading information
     */
    public function ticker(string $book = null) {
        $method = "ticker";
        $params['book'] = $book;
        return $this->_public_api($method, $params);
    }

    /**
     * Retrieves list of all open orders
     *
     * @param string $book with trading pair
     * @param bool $group group orders with the same price (0-false; 1-true)
     * @return json dictionary with "bids" and "asks".
     */
    public function order_book(string $book = null, bool $group = null) {
        $endpoint = "order_book";
        $params = array();
        if (!is_null($book)) {
            $params['book'] = $book;
        }
        if (!is_null($group)) {
            $params['group'] = $group;
        }
        return $this->_public_api($endpoint, $params);
    }

    /**
     * Retrieves list of recent trades
     *
     * @param string $book with trading pair
     * @param string $time with time frame for transaction export ("minute" - 1 minute, "hour" - 1 hour)
     * @return json dictionary with list of transactions
     */
    public function transactions($book = null, $time = null) {
        $endpoint = "transactions";
        $params = array();
        if (!is_null($book)) {
            $params['book'] = $book;
        }
        if (!is_null($time )) {
            $params['time'] = $time;
        }
        return $this->_public_api($endpoint, $params);
    }

}
?>