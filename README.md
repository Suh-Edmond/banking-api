## Banking API

A Simple Banking API for a Fake Banking System. It is aimed at simulating the behavior of a real Banking System.
This API provides the following Services

- Authenticate Users(Create user accounts and Login users)
- Create Bank Accounts for autheticated users
- Retrieve all accounts by users
- Check Account Balances
- Perform bank transfer between accounts
- Retrieve transfer history for a given account

### Framework used
- Laravel 8.0

### Package used
- Laravel Sanctum for authentication
- Laravel Spatie for Role/Permission Management

### Server Requirements
- PHP 7.4
#### Noted
The API makes used of Laravel Spatie for Role/Permission Management, but only the roles are been used for authorization.
## API Design
Below is an image of the Database design of the of the API.
![Database design](/banking-api/public/assets/ER_Diagram.jpg)
### How to start locally
- Clone the project using the link, the latest code is on the *main* branch
- Open it in your favorite IDE and run composer install
- Generate the app key by running *php artisan key:generate*
- Setup you daabase connection in you *.env* file
- Run the migration using the command *php artisan migrate*
- Seed the database with the started data
- You can view all the endpoints been exposed from the /routes/api.php file.

### The fully documentation of the API can be found by following the link below
- [Link to the postman workspace](https://red-crater-101914.postman.co/workspace/69a59689-bdcf-4ac1-b486-b40d3b4b91ed)
- [Link to the AccountType Collection](https://documenter.getpostman.com/view/16937718/2s8YzRy2uh)
- [Link to the Authentication collection](https://documenter.getpostman.com/view/16937718/2s8YzRy2z7)
- [Link to Bank Account collection](https://documenter.getpostman.com/view/16937718/2s8YzRy2z8)
- [Link to the Transaction collection](https://documenter.getpostman.com/view/16937718/2s8YzRy2zA)
- [Link to the Transfer Type collection](https://documenter.getpostman.com/view/16937718/2s8YzRy2zB)


