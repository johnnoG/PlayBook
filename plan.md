# Work Plan

## Front-End

- **Login Page - Omer**

  - Finish JS to receive email and password and send to back end for validation

- **Sign Up Page - Omer**

  - Add type to input tags
  - Create JS function to receive all data and send to back end
  - JS should also send the data to our mail

- **Football Page**

  - Finish design: information about the game (time, location, type, number of registered, register option)
  - Create JS functions to handle use cases
  - Send data to back end

- **Profile Page - Omer**

  - Finish design: the user should be able to see his ratings and previous games
  - Create JS functions to handle use cases
  - The "star" and "profile" will direct to this page

- **Game Page - Tohar**

  - Finish design: the page will show all the players in the game (name, position, etc.) and receive data from back end
  - Create JS functions to handle use cases

- **Finish Page**

  - Finish design: the user will have the option to rate the players from the game and the court
  - Create JS functions to handle use cases

- **General**
  - Every page should have navigation to all other pages

## Back-End

- Create DB and relevent tables: add schema to the project as a different file **Tohar**
- Create DB instance **Yonatan**
- Create Server **Yonatan**
- Create login endpoint: validate data in request from the DB **Yonatan**
- Create sign-up endpoint: store received data in DB **Yonatan**
- Create profile page endpoint: send all user data from DB to front end
- Create game page endpoint: get relevant data from DB and send to front end
- Create finish page endpoint: save ratings to relevant tables in DB

## Structue

- src/api: all endpoints
- src/services: all functions that the endpoint using
