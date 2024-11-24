<?php
// This is just for very basic implementation reference, in production, you should validate the incoming requests and implement your backend more securely.
// Please refer to this docs for snap popup:
// https://docs.midtrans.com/en/snap/integration-guide?id=integration-steps-overview

namespace Midtrans;

require_once dirname(__FILE__) . '/../../Midtrans.php';
// Set SB-Mid-server-SdGSNrMDhqUgP4KJM_0hTR3O
// can find in Merchant Portal -> Settings -> Access keys
Config::$serverKey = 'SB-Mid-server-SdGSNrMDhqUgP4KJM_0hTR3O';
Config::$clientKey = 'SB-Mid-client-uw81o6eb7cacAn_V';

// non-relevant function only used for demo/example purpose
printExampleWarningMessage();

Config::$appendNotifUrl = "http://localhost/fp/dashboard.php";
Config::$overrideNotifUrl = "http://localhost/fp/dashboard.php";
// Uncomment for production environment
// Config::$isProduction = true;
Config::$isSanitized = Config::$is3ds = true;

// Required
$transaction_details = array(
    'order_id' => rand(),
    'gross_amount' => 94000, // no decimal allowed for creditcard
);
// Optional
$item_details = array(
    array(
        'id' => 'a1',
        'price' => 94000,
        'quantity' => 1,
        'name' => "Apple"
    ),
);
// Optional
$customer_details = array(
    'first_name'    => "Andri",
    'last_name'     => "Litani",
    'email'         => "andri@litani.com",
    'phone'         => "081122334455",
    'billing_address'  => $billing_address,
    'shipping_address' => $shipping_address
);
// Fill transaction details
$transaction = array(
    'transaction_details' => $transaction_details,
    'customer_details' => $customer_details,
    'item_details' => $item_details,
);

$snap_token = '';
try {
    $snap_token = Snap::getSnapToken($transaction);
} catch (\Exception $e) {
    echo $e->getMessage();
}
echo "snapToken = " . $snap_token;

function printExampleWarningMessage()
{
    if (strpos(Config::$serverKey, 'your ') != false) {
        echo "<code>";
        echo "<h4>Please set SB-Mid-server-SdGSNrMDhqUgP4KJM_0hTR3O from sandbox</h4>";
        echo "In file: " . __FILE__;
        echo "<br>";
        echo "<br>";
        echo htmlspecialchars('Config::$serverKey = \'<SB-Mid-server-SdGSNrMDhqUgP4KJM_0hTR3O>\';');
        die();
    }
}

?>

<!DOCTYPE html>
<html>

<body>
    <button id="pay-button">Pay!</button>
    <!-- TODO: Remove ".sandbox" from script src URL for production environment. Also input SB-Mid-client-uw81o6eb7cacAn_V in "data-client-key" -->
    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="<?php echo Config::$clientKey; ?>"></script>
    <script type="text/javascript">
        document.getElementById('pay-button').onclick = function() {
            // SnapToken acquired from previous step
            snap.pay('<?php echo $snap_token ?>');
        };
    </script>
</body>

</html>