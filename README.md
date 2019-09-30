Exercise
========================

Create a REST API Service using Symfony 3 that provides a simple order management system to be consumed. The service should provide two entities/resources to be stored in a MySQL database: customers and orders.

Implement as many CRUD REST Calls as possible in at least one of the resources but please call at worst the two different resources. Present and accepted the data in JSON format allowing also a secure communication, as using HTTP Basic authentication or other securiry measures.

Please provide information about the decisions taken and how you would improve it in the future, especially concerning security and authentication.

Docker (Optional)
--------------

Inside the directory you can run directly:

`docker-compose up`

You can access the webserver container running ngingx php 7.2 with:

`docker exec -it --user application webserver bash`

Also the database container running mysql 5.6 with:

`docker exec -it database bash`

Ports exposed:

* Web: 80
* Database: 3306

Database:

* symfony

Users:

* user: root

* password: symfony

* user: symfony

* password: symfony

Running the app
--------------

Please remember to run in the command line interface:

`composer install`

Then you need to run the doctrine migrations in order to create the database:

`php bin/console d:m:m`

This will generate a few tables and a basic Auth user token that you can use in the REST Api.

Testing the app
--------------

Entities:

* Customer
* CustomerOrders
* User

Header that need to be used in the requests (you can change the token for a valid user in the database):

`X-AUTH-TOKEN:1eb6ea66-a6b9-466c-9692-c1e459745262`

Endpoints:

* GET localhost/customers

This endpoint will show a list of Customers with their CustomerOrders in JSON format.

* GET localhost/customers/{id}

This endpoint will show a single Customer with his CustomerOrder in JSON format.

* POST localhost/customers

This endpoint will store a single Customer in the database.

`{
    "firstName": "test",
	"lastName":"test",
	"email":"test3@test.com",
	"phone":"test",
	"addressLine1":"test",
	"city":"test",
	"state":"test",
	"postalCode":"test",
	"country":"test"
}`

* PUT localhost/customers/{id}

This endpoint will update a single Customer in the database.

`{
	"firstName":"test2",
	"lastName": "test3"
}`

* DEL localhost/customers/{id}

This endpoint will destroy a single Customer with his CustomerOrders in the database.

* GET localhost/customers/{id}/orders

This endpoint will show a list of CustomerOrders that belongs to the Customer ID.

* POST localhost/customers/{id}/orders

This endpoint will store a CustomerOrder that belongs to the Customer ID.

`{
	"orderDate": "2019-06-10 00:00:00",
	"shippedDate":"2019-06-11 00:00:00",
	"status":"test",
	"comments": "test"
}`
