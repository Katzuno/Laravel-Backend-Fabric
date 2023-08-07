## Requirments:

- docker-compose
- php
- composer
- (optional) make

## How it works

At the very basic level the laravel service exposes a CRUD and search api through RecordsController.

This api also does a RabbitMQ publish on queue ``records_to_analyze`` when a new record is created. 
We are doing this not to block requests while we are searching for matches and maybe processing that information in our NEST JS service.

This service also implements a consumer that you can run through command which listens for new messages in the ``records_analyzed`` queue from rabbitmq.

When processing the messages from the queue it updates a record in the database.

## How Nest service works

When the nest service is opened it automatically consumes messages from the ``records_to_analyze`` queue (behavior defined in app controller).

Those messages are consumed by interogating omdb api service on both scenario (if we have imdb_id or if we don't have on the payload).

If it got data from the omdb api it publishes the message through rabbit-mq publisher to the ``records_analyzed`` queue.

## Run the app

The app was containerized using sail. 

To run the app for the first time you have to use the command ``make up`` that will automatically install the compose packages, including sail.
If you are on the first run, is run please open also the consumer with ``make up-consumer``.

After the first run it is enough to do ``make up-full`` or the associated command from the makefile.
This will start the app and also the ``records_analyzed`` consumer.

To run the unit tests use ``make test``

If you encounter problems make sure you have the .env file created and migrations are executed (if not use ``make migrate``)

## How to use

The API has a full swagger docs at the address ``http://localhost/api/documentation``
Once everything is up and running you can use it from swagger or from the React interface.


## What can be better

- Functional tests
- Testing environments for elastic and rabbit
- Code organization
- Think about security, type of queues, requeue, ack, nack, etc.
- Elasticsearch filters by different fields
- Exceptions handling
- Logging
- Other system architecture decisions should be changed (improved) based on specific use cases.
