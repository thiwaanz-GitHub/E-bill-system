# Electricity Bill Management System

 This system is used to store meter reading data and let customers check their last bill with a breakdown of the charges.

---

## Guidelines For E-Bill Management System Users

- ### Customer ###

  - You must have to click on ***Customer*** button in Home page if you want to work on with system.

  - You have to enter your  ***Account Number*** to the input field displayed in customer page.
  
  - You must have to enter correct  ***Account Number*** to the input field. If you enter a invalid account number system will display an error message. Then you have to try again with your correct  ***Account Number***.
  
  - If you entered your correct  ***Account Number*** then system will generate your monthly electricity activities as well as your personal details.

- ### Meter Reader ###

        > Username : admin
        > Password : admin

  - You must have to click on  ***Meter Reader*** button in home page if you want to work on with system.
  
  - If you go through the right direction then system will display the  ***Meter Reader's Login Page***.
  
  - Then you have to enter your  ***Registerd Username*** and  ***Password*** to enter the System.
  
  - If your ***Username*** or ***Password*** or both input fields are empty or if some enterd input data will be wrong then you click on the login button you can see an ***Error Message*** displayed on your screen. Then you can refresh the web page then you can enter your correct ***Username*** and ***Password*** to login with the system.
  
  - If you loged into the system with correct ***Username*** and ***Password*** then you can see on your display ***Meter Reading Page.***
  
  - Meter Reading Page consider three input fields ***Account Number, Date and Meter Reading.*** Then you can enter meter reading deatails of existing customers in your database.
  
  - The ***Account Number*** field will be filled with correct account numbers of customers. If you enter a wrong account number then you can see an error message like ***"Account Number Does Not Exist."***
  
  - Also you have to complete all three input fields before you enter the ***Save*** Button. If you does not completed all three input fields you may have display input field requred message.
  
  - If you go through the right way you can save Meter Reading details of each customer according to the customers account number. So you can check the database which you enterd recorde does added.

---

## E-Bill API Documentation

E-Bill API that will allow the public to access the last two meter readings and meter reading dates by giving an ***Account Number.***

### Endpoint: /api/api.php/?acc_no={account_number}

    http://localhost/E-bill-system/api/api.php/?acc_no={account_number}

#### Parameters

- `acc_no` (required) Customer E-Bill Account Number

#### HTTP Method

    GET

#### Request Example

    http://localhost/E-bill-system/api/api.php/?acc_no=10000001

#### Response Example

```json
{
    "acc_no":"10000001",
    "last_meter_reading":"360",
    "last_meter_reading_date":"2023-05-09",
    "previous_meter_reading":"220",
    "previous_meter_reading_date":"2023-03-10"
}
```

#### Error Messages

- `404 Not Found`: Account Number does not exist.

#### Usage Guideline for API

- Use this endpoint to retrieve information for a specific account.

- You must provide a valid Account Number to retrieve user information.
