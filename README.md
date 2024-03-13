# BlogPhp

## Table of Contents

1. [Description](#description)
2. [Main Features](#main-features)
3. [Technologies Used](#technologies-used)
4. [Library](#library)
5. [Installation](#installation)
6. [Contributing](#contributing)
7. [FAQ](#faq)

## Description

BlogPhp is a web application for article blogging with administrator and user management. The goal is to provide a platform for articles and comments publication, with moderation features.

## Main Features

- CRUD (Create, Read, Update, Delete) for articles and comments
- User management with different roles (administrator, user)
- Article publishing by administrators and users
- Comments on articles with moderation capability
- Form security against CSRF (Cross-Site Request Forgery) attacks

## Technologies Used

- PHP 8.2.10
- MySQL (or another relational database)
- HTML
- CSS
- JavaScript

## Library
- cocur/slugify
- pecee/simple-router
- phpmailer/phpmailer
- psr/container
- twig/twig
- vlucas/phpdotenv

## Installation

1. Clone the GitHub repository: `git clone https://github.com/KenKaneki-42/P5_BlogPHP.git`
2. Install PHP dependencies with Composer: `composer install`
3. Import the provided database in the `database` folder or run migrations and seeds
4. Configure the database connection information in the `config/database.php` file
5. Start the built-in PHP server: `php -S localhost:8000`
6. Access the application in your browser at `http://localhost:8000`

## Configuration
1. For Database configuration, please refer to ConnectionDbSample.php erase the suffixe Sample for the file name and the class name et set up it with your own credentials.
2. For Mailer configuration, please refer to MailerConfig.php erase the suffixe Sample for the file name and the class name et set up it with your own credentials.

## Contributing

If you want to contribute to BlogPhp, please follow these steps:

1. Fork the repository
2. Create a new branch (`git checkout -b feature/new-feature`)
3. Commit your changes (`git commit -am 'Add a new feature'`)
4. Push the branch (`git push origin feature/new-feature`)
5. Create a pull request

## FAQ

### Q: How can I create a new article?

A: To create a new article, log in as an administrator or authenticated user, then navigate to the article creation page.

### Q: Are comments moderated?

A: Yes, comments undergo moderation by administrators before being published on the blog.

### Q: How can i addi an Admin User

To grant an existing user admin privileges, follow these steps:

1. Ensure the application is properly installed and configured as per the [Installation](#installation) and [Configuration](#configuration) sections.
2. Open a terminal and navigate to the root directory of your BlogPhp application.
3. Execute the `AddAdminUser.php` script located in the `config/Command` directory by running the following command:

    ```
    php config/Command/AddAdminUser.php
    ```

4. When prompted, enter the email address of the user you wish to promote to an admin role.

    ```
    Please write the email of the user you want to add role as ROLE_ADMIN
    ```

5. If a user with the provided email exists in the database, they will be granted admin privileges, and you will see a confirmation message:

    ```
    User with email example@example.com has been added as admin
    ```

    If the user does not exist, you will receive an error message indicating so.

6. The user with the specified email will now have admin access and can perform administrative actions within the BlogPhp application.
