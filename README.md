# BlogPhp

## Table of Contents

1. [Description](#description)
2. [Main Features](#main-features)
3. [Technologies Used](#technologies-used)
4. [Installation](#installation)
5. [Contributing](#contributing)
6. [FAQ](#faq)

## Description

BlogPhp is a web application for article blogging with administrator and user management. The goal is to provide a platform for article and comment publication, with moderation features.

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

## Installation

1. Clone the GitHub repository: `git clone https://github.com/KenKaneki-42/P5_BlogPHP.git`
2. Install PHP dependencies with Composer: `composer install`
3. Import the provided database in the `database` folder or run migrations and seeds
4. Configure the database connection information in the `config/database.php` file
5. Start the built-in PHP server: `php -S localhost:8000`
6. Access the application in your browser at `http://localhost:8000`

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
