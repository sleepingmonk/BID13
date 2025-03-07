<?php
/**
 * @file
 * Phone number validation function for BID13 quiz.
 */

require __DIR__ . "/../../vendor/autoload.php";
use telesign\sdk\phoneid\PhoneIdClient;

// Give the terminal user a little help.
// Don't waste a call if we don't have enough args.
if (empty($argv[1])
  || in_array($argv[1], ['--help', '-h'])
  || empty($argv[3])) {

  echo <<<END

  This script checks to see if a phone number is a valid land line or mobile number.

  USAGE:  php src/phoneId/isValid.php customer_id api_key phone_number

          phone_number - Full number without spaces or symbols. 12345551212



  END;

  return 0;
}

// Set up our values.
$customer_id = $argv[1];
$api_key = $argv[2];
$phone_number = $argv[3];

/**
 * Validate a phone number using the phoneid api.
 *
 * @param string $customer_id
 *   The telesign customer id.
 * @param string $api_key
 *   The telesign api key.
 * @param [type] $phone_number
 *   The phone number to validate.
 * @return void
 */
function validatePhoneNumber($customer_id = NULL, $api_key = NULL, $phone_number = NULL) {

  // Valid, Fixed Line, or Mobile.
  // I don't see a "Valid" type in the phone type codes.
  // https://developer.telesign.com/enterprise/docs/codes-languages-and-time-zones#phone-type-codes
  $valid_phone_types = [
    '1',
    '2',
  ];
  // Call the API via the SDK.
  $data = new PhoneIdClient($customer_id, $api_key);
  $response = $data->phoneid($phone_number);

  if ($response->ok) {
    return in_array($response->json['phone_type']['code'], $valid_phone_types);
  }

  // Returning status description to give a clue when response is not ok.
  return $response->json['status']['description'];
}

$result = validatePhoneNumber($customer_id, $api_key, $phone_number);

var_dump($result);
