# lightify-api
PHP Implementation of Osram Lightify API

Author: Robin Lehrmann
http://developer.nrw

- install with `composer require role/lightify-api`

- create an instance of LightifyApi class with guzzle_client and credentials as parameters like:

`$lightifyApi = new Role\Lightify\LightifyApi($guzzleClient, $url, $userName, $password, $serialNumber)`

you can use the DeviceManager or the GroupManger to control your home

     // get a list of all devices
     $devices = $deviceManager->getList();
    
     // fade device 1 out in 10 seconds
     $deviceManager->toggle(1, false, 10);
    
     // switch off device 2 without fade
     $deviceManager->toggle(2, false);
    
     // dim device 2 to 0.2 in 10 seconds
     $deviceManager->dim(1, 0.2, 10);
     
     // switch color of a rgb light
     $deviceManager->switchColor(1, 'FF00FF');
     
     // switch temparature of device
     $deviceManager->switchColor(1, 'FF00FF');
     
Contributions are welcome