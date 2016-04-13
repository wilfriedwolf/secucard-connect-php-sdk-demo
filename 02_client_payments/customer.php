<?php

/**
 * THIS IS SAMPLE command line script to create payment.customers object
 * To use it, update @variables with correct values and run command "php customer.php"
 */

use SecucardConnect\Auth\ClientCredentials;
use SecucardConnect\Client\DummyStorage;
use SecucardConnect\Product\Common\Model\Address;
use SecucardConnect\Product\Common\Model\Contact;
use SecucardConnect\Product\Payment\Model\Customer;
use SecucardConnect\SecucardConnect;
use SecucardConnect\Util\Logger;

require_once __DIR__ . '/../shared/init.php';

$config = [
    // demo server
    'base_url' => 'https://connect-dist.secupay-ag.de',
    // live
    //'base_url' => 'https://connect.secucard.com',
    'debug' => true
];

$logger = new Logger(fopen("php://stdout", "a"), true);

$store = new DummyStorage();

// payment product uses client_credentials auth so either provide valid refresh token here or obtain token by processing
// the auth flow, see \SecucardConnect\Auth\ClientCredentials
$cred = new ClientCredentials('@your-client-id', '@your-client-secret');

$secucard = new SecucardConnect($config, $logger, $store, $store, $cred);

$service = $secucard->payment->customers;

// You may obtain a global list of available customers
$customers = $service->getList();
if ($customers === null) {
    throw new Exception("No Customers found."); // Should not happen
}

// new customer creation:

$contact = new Contact();
$contact->salutation = 'Mr.';
$contact->title = 'Dr.';
$contact->forename = 'John';
$contact->surname = 'Doe';
$contact->companyname = 'Testfirma';
$contact->dob = '1971-02-03';
$contact->birthplace = 'MyBirthplace';
$contact->nationality = 'DE';
$contact->email = 'example@example.com';
$contact->phone = '0049-123456789';

$address = new Address();
$address->street = 'Example Street';
$address->street_number = '6a';
$address->city = 'ExampleCity';
$address->country = 'Deutschland';
$address->postal_code = '01234';

$contact->address = $address;

$customer = new Customer();
$customer->contact = $contact;

try {
    $customer = $service->save($customer);
} catch (\Exception $e) {
    echo 'Error message: ' . $e->getMessage() . "\n";
}

if ($customer->id) {
    echo 'Created Customer with id: ' . $customer->id . "\n";
    echo 'Customer data: ' . print_r($customer, true) . "\n";
} else {
    echo 'Customer creation failed';
}