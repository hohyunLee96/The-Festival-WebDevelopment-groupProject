# The Festival -Group-5 IT2A

It contains a docker configuration with:
* NGINX webserver
* PHP FastCGI Process Manager with PDO MySQL support
* Azure Mysql Db
* PHPMyAdmin

## Installation

1. Install Docker Desktop on Windows or Mac, or Docker Engine on Linux.
1. Clone the project

## Usage

## Running the Application
**Note:** docker-compose down -v to delete al the volume of the containers and set it again
To run the application, use the command: 
```bash
docker-compose down -v
docker-compose up
```

##Live App :
https://thefestivalinhollland.000webhostapp.com

Login Credentials:

Admin 
email Address:
```bash
test@inholland.nl
```
Password:
```bash
12
```
Customer
email Address:
```bash
eerwin@gmail.com
```
Password
```bash
12
```
Employee
email Address:
```bash
bijay@gmail.com
```
Password
```bash
12
```

> **_NOTE:_**
We deployed the website with 000webhost, but we faced issues with the database connection due to the limitation of maximum connection users allowed. As a result, we switched to using the database from Azure, which may cause some delays in loading pages. There are also some errors occurring on the deployed website for AJAX requests, such as 'net::ERR_HTTP2_PROTOCOL_ERROR', which we are currently investigating. Additionally, the CAPTCHA feature is not valid on 000webhost.

There is a Decision List available in the directory if needed.
