<?php
/**
 * CryptoSell Wallet API
 * Cron API for analyzing transactions
 * Will ask RPC client to each wallet transactions incoming if a transaction has been sent
**/

require "sqlink.php";

$trans = CryptoSQL::getTransactionByAddress($_POST['address']);
Logger::log("Checking transaction $trans->id");
$accountAddress = $trans->creditWalletAccount;
$received = Bitcoin::getReceivedByAccount($accountAddress);
$txid = $trans->id;
if($trans->iStatus != 2 && $received > 0)

{
    Logger::log("I received ". $received);
    CryptoSQL::updateReceivedTransaction($trans, $received);
}
$toReturn["received"] = Bitcoin::getReceivedByAccount($accountAddress);
$toReturn["required"] = $trans->requiredAmount;
$toReturn["currency"] = $trans->currency;
$toReturn["status"] = $trans->iStatus;
Logger::log("Returning JSON: " . json_encode($toReturn));
echo json_encode($toReturn);
