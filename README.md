# Test Assignment REST-Service - Devices
## _Adrian Lawley_

 ## TODOs: json error handling
 
 ### Installation
 
 **Currently the Environment is set to dev**
 
 **For production change docker-compose.yaml:13 or consider adding a new .env file**
 
Clone repository from https://github.com/Taff-Lawley/wg_device_rest.git
 ```sh
 git clone https://github.com/Taff-Lawley/wg_device_rest.git
 ```
 
```sh
composer install
```

Accessing the terminal:
```sh
docker-compose exec symfony /bin/bash
```
  
Create SQLlite Database with correct schema:
```sh
php bin/console doctrine:schema:create
```

With have the option to automagically import any CSV's in src/Resources/deviceCsvFiles with the following command
```sh
php bin/console app:import-csv
``` 

Additionally we can use a fixture to load dummy data (see src/DataFixtures/AppFixtures.php):

**CAUTION: This action will purge the database before adding new data**

```sh
php bin/console doctrine:fixtures:load
``` 

Starting the server
```sh
docker-compose up --build
``` 

If you haven't seen any nasty warnings by now, you should be good to go.

## Creating, reading, updating and deleting devices.

#####Retrieve all devices as JSON
This can be done by either calling http://127.0.0.1:8888/devices in a browser
or via a cURL request

```sh
curl -X GET -H "Content-Type: application/json" http://127.0.0.1:8888/devices
```

#####Retrieve a single device as JSON
This can also be done by either calling http://127.0.0.1:8888/device/1 in a browser
or via a cURL request (Note: if you used a fixture after importing from a CSV, ID 1 will no be available) resulting in a NotFoundHttpException
```sh
curl -X GET -H "Content-Type: application/json" http://127.0.0.1:8888/device/1
```

#####Create a new device
```sh
curl -X POST -H "Content-Type: application/json" -d '{"device_id": 32,"device_type":"Smart TV","damage_possible":false}' http://127.0.0.1:8888/device/create
```

#####Edit a single device as JSON
```sh
curl -X POST -H "Content-Type: application/json" -d '{"device_id": 1337,"device_type":"Bessere Kaffeemaschine","damage_possible":false}' http://127.0.0.1:8888/device/edit/1
```

#####Delete a single device
```sh
curl -X DELETE -H "Content-Type: application/json" http://127.0.0.1:8888/device/delete/27
```
