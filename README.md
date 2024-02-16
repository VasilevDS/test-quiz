# test quiz

## Setup:

- Building the project and running containers:
```shell
make init
```

- Running the installation of composer packages, perform migrations and fill the database with data for the test quiz.:
```shell
make update-app
```

- After running these two commands you can go to the page: http://localhost:8080/quiz/list
  The list should include a test quiz.

