# CloudKit-PHP

Today I found this fantastic [gist](https://gist.github.com/Mauricevb/87c144cec514c5ce73bd) by [Mauricevb](https://gist.github.com/Mauricevb) that demonstrates how to communicate with CloudKit from PHP. I already had a previous project that used a backend similar to CloudKit so by replacing the web request code with his I've created this PHP library for CloudKit. It's very much a work in progress and it would be great if anyone could contribute to fill in the missing methods.

## Status

Fetching, querying and saving records is partially working.

## Development Resources

[CloudKit JS Reference](https://developer.apple.com/library/ios/documentation/CloudKitJS/Reference/CloudKitJavaScriptReference/index.html#//apple_ref/doc/uid/TP40015359)

[Accessing CloudKit Using a Server-to-Server Key](https://developer.apple.com/library/ios/documentation/DataManagement/Conceptual/CloutKitWebServicesReference/SettingUpWebServices/SettingUpWebServices.html#//apple_ref/doc/uid/TP40015240-CH24-SW6)

[CloudKit Web Services Reference](https://developer.apple.com/library/ios/documentation/DataManagement/Conceptual/CloutKitWebServicesReference/Introduction/Introduction.html#//apple_ref/doc/uid/TP40015240-CH1-SW1)


## Example
```
<?php
require dirname(__FILE__).'/autoload.php';
use CloudKit\Container;
use CloudKit\Record;
use CloudKit\Query;
use CloudKit\Location;

$container = new Container('iCloud.com.container', // containerID
  '9bbf2b399e9cd74x372bb4ec11cb5x1b7f0d73db16a24x08a018', // keyID, see Accessing CloudKit link above 
  'eckey.pem', // private key file, again see accessing CloudKit.
  'development'); // environment

$query = new Query("Venue");
//$query->filter('name', 'Java Earth');
$query->filter('name', '=', 'Java Earth');
//$query->filterIn('name', ['Java Earth']);
$response = $container->getPublicCloudDatabase()->performQuery($query, [ 'resultsLimit' => 1 ]);

if($response->hasErrors()) {
    echo $response->getErrors()[0]->getReason();
    return;
}

$records = $response->getRecords();


$record = NULL;
if(count($records) > 0) {
    $record = $records[0];
}else{
    $record = new Record('Venue');
}

$record->setField('name', 'Java Earth');
$record->setField('formattedAddress', '4978 Cass St, San Diego, CA 92109, United States');
$record->setField('location', new Location(32.805509, -117.254510));
$response2 = $container->getPublicCloudDatabase()->saveRecords([$record]);
if($response2->hasErrors()) {
    var_dump($response2->getErrors()[0]);
}else{
    echo "Success\n";
}

?>
```
