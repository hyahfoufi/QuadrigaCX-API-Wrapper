# QuadrigaCX-API-Wrapper
A PHP API Wrapper for the QuadrigaCX Cryptocurrency Trading Platform

# Usage
First, include the classes in your project.

#### Public:

`require 'QuadrigaPublicAPI.php';`

#### Private:

`require 'QuadrigaPrivateAPI.php';`

Next, create a new instance of the class you want to use:

`$PublicAPI = new QuadrigaPublicAPI();`

`$PrivateAPI = new QuadrigaPrivateAPI("YOUR_API_KEY", "YOUR_API_SECRET", "YOUR_CLIENT_ID");`

You now have access to all of the available functions.

# Examples

#### Public:

Get the price of Bitcoin in Canadian Dollars

`$PublicAPI->ticker("btc_cad");`

List all open orders for Bitcoin/CAD

`$PublicAPI->order_book("btc_cad")`

Retrieve list of all Bitcoin/USD transactions

`$PublicAPI->transactions("btc_usd")`

#### Private:

Get your account balances

`$PrivateAPI->balance()`

Get a list of your transactions

`$PrivateAPI->user_transactions()`

List your open orders

`$PrivateAPI->open_orders()`

Return details of a specific order (Replace ORDER_ID with an order ID)

`$PrivateAPI->lookup_order("ORDER_ID")`

Return details of several order ID's (Pass an array of order IDs)

`$PrivateAPI->lookup_order(["ORDER_ID", "ORDER_ID2"])`

Cancel an order

`$PrivateAPI->cancel_order("ORDER_ID")`

Place a limit order to buy 0.5 Bitcoin at 12000 Canadian Dollars

`$PrivateAPI->limit_order("buy", 0.5, 12000, "btc_cad")`

Place a limit order to sell 0.8 Litecoin at 180 US Dollars

`$PrivateAPI->limit_order("buy", 0.8, 180, "ltc_usd")`

Buy 0.4 Ether at the current market price in Canadian Dollars

`$PrivateAPI->buy_market("buy", 0.4, "eth_cad")`

Get your deposit address for Bitcoin

`$PrivateAPI->deposit("btc")`

Withdraw 0.8 Bitcoin

`$PrivateAPI->withdraw("btc", 0.8, "YOUR_WALLET_ADDRESS")`

# Donate

Donations are appreciated

Bitcoin: `1Kx8ZpzF3AJG77TuJFh6DQNiggBfQVsPkD`

BitcoinCash: `1GCq4wnue6kfL2ej2BRTM2iZYAUV3b9jME`