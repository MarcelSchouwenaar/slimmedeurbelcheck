# Doe de Check met een Goed Gesprek

This website inspires neighbors to make agreements about the use of smart doorbells in their area. An applicant can fill out a form to confirm they agree about the setup of the smart doorbell. After confirming their email address, they receive a sticker from our organization to place next to their doorbell.

## Database

### Table 1: Neighbors
- Id
- Name (varchar)
- Email (varchar)
- Follow-up (bool)
- ConfirmationToken (varchar)

### Table 2: Applications
- Id
- ApplicationData (varchar)
- NeighborOneId (neighbor_id)
- NeighborOneApproval (bool)
- NeighborOneFeedback (varchar, optional)
- Zipcode (varchar)
- Street (varchar)
- House Number (varchar)
- Addition (varchar)

## General recommendations
- Don't use a front-end framework or UI-library; stick to plain JS and CSS.
- Stack: PHP + MySQL, assume mail() function works.

## Installation

1. Clone the repository:
    ```
    git clone https://github.com/MarcelSchouwenaar/slimmedeurbelcheck.git
    ```
2. Copy `_env.php` to `env.php` and fill in your database credentials.
3. Run `install.php` in your browser (e.g. `http://localhost/path/to/install.php`) to set up the database.
4. Remove or restrict access to `install.php` after installation for security.
5. Make sure your web server has write access to the project folder if needed.

## Pages

### Install - install.php
- Can only be visited once per environment (localhost / production)
- Sets up database and env.php

### Home - index.php
- General introduction about project.
- CTA to apply for sticker

### Form - form.php
- Form to apply for sticker
    - Form-group 1: Application (radio-inputs for various checks)
    - Form-group 2: Applicant (Name, email, approve for follow-up)
    - Form-group 3: Address (zipcode, street, housenumber, addition)
- Submitting form:
    - Creates entry in table applications
    - No duplicate checks; always creates new entries
    - Applicant receives email to confirm their application

### Confirm - confirm.php
- GET payload contains
    - neighbor_id (encrypted to avoid spoofing)
    - token (encrypted to avoid spoofing)
- Updates 'NeighborOneApproval' in database (meaning that neighbor_id approves application)
- Sends 'Mail 3: Approved' to applicant

### About - about.php
- Plain text about the project 

### Contact - contact.php
- Contact form with 'name, email, message'
- Forwards mail to 'info@marcelschouwenaar.nl'
- Sends confirmation email to sender with their own message in body

### Includes
- env.php
    - Settings for database connection
- db.php
    - Setup database connection
- nav.php
    - Responsive for desktop and mobile
    - Current page is highlighted in the menu
- head.php
    - Included in head
    - Includes styles, meta-tags, og-tags, etc.
- footer.php
    - Plain text
    - Site map

### Other
- `.gitignore` for PHP

## Mail 1: Confirm
- The applicant receives a personal version of this email
- The email has a link:
    - Approve --> confirm.php
- Ensure the link is unique to the applicant and application

## Mail 3: Approved
- The applicant receives this email
- Email explains that sticker will be sent asap
