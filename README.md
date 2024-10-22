## Ticket Sale System - Modular Monolith

This repository serves as a test for hiring PHP/Laravel developers. The goal is to implement a ticket sale system using a modular monolith architecture in Laravel. Authentication logic is not required to be implemented. The system manages venues, events, and ticket sales, with a focus on modular design to facilitate easy maintenance and scalability.

You can use the pre-installed Laravel Modules package to create modules. New modules will be placed in the `Modules/` folder. Detailed instructions for using this package can be found [here](https://laravelmodules.com/docs/v10/creating-a-module).

The repository includes pre-built migrations and seeders for venues and events. To set up the database, run the migrations and seeders with the following commands:
```bash
php artisan migrate
php artisan db:seed
```
Additionally, you are required to write feature tests to ensure that the core functionality, such as listing events and purchasing tickets, works as expected. Tests should cover:

- Listing events with available tickets
- Purchasing tickets (including error handling for duplicate emails and sold-out events)
Feature tests should be written using Laravel's built-in testing framework, and can be run using the following command:
```bash
php artisan test
```

### Task Description

The system consists of the following modules:

#### Modules
- **Venue Module:** Manages the venues where events take place. This module provides venue details like the name and capacity of the venue.
- **Event Module:** Manages events associated with venues. It retrieves event details such as the number of available tickets, venue information, and the deadline for ticket sales.
- **Payment Module:** Handles ticket purchases. This module ensures that tickets are available before processing a purchase, checks for duplicate purchases using the same email, and updates ticket availability after a successful transaction.

#### API Endpoints
- **GET /api/events:** Returns a list of events with the following details:
  - Event Name
  - Available Tickets (calculated as venue capacity minus the number of sold tickets)
  - Venue Name
  - Ticket Sales End Date
    
Example of a Successful Response:
```json
[
    {
        "event_name": "Event 1",
        "available_tickets": 100,
        "venue_name": "Venue 1",
        "ticket_sales_end_date": "2022-12-31 23:59:59"
    },
    {
        "event_name": "Event 2",
        "available_tickets": 50,
        "venue_name": "Venue 2",
        "ticket_sales_end_date": "2022-12-31 23:59:59"
    }
]
```
- **POST /api/events/{event_id}/purchase:** Allows a user to purchase a ticket by providing their email address.
    - Request Body:
        ```json
        {
            "email": ""
        }
        ```
    - Successful Response (200):
      ```json
      {
          "transaction_id": ""
      }
      ```
    - Error Response (400):
      - Email already used:
        ```json
        {
            "error": "Email already used for this event."
        }
        ```
      - No available seats:
        ```json
        {
            "error": "No available seats for this event."
        }
        ```
      - Event is closed:
          ```json
          {
              "error": "The event is closed."
          }
          ```
