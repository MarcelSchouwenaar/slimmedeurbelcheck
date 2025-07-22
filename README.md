# Doe de Check met een Goed Gesprek
This website inspires neighbors to make agreements about the use of smart doorbells in their area. Two neighbors can fill out a form together to confirm they agree about the setup of the smart doorbell. If both respond to the confirmation email, they receive a sticker from our organization to place next to their doorbell.

## Database
### Table 1: Neighbors
- Id
- Name (varchar)
- Email (varchar)
- Follow-up (bool)

### Table 2: Applications
- Id
- ApplicationData (varchar)
- NeighborOneId (neighbor_id)
- NeighborTwoId (neighbor_id)
- NeighborOneApproval (bool)
- NeighborTwoApproval (bool)
- NeighborOneFeedback (varchar, optional)
- NeighborTwoFeedback (varchar, optional)
- Zipcode (varchar)
- House Number (varchar)
- Addition (varchar)

## General recommendations
- Don't use a front-end framework or UI-library; stick to plain JS and CSS. 
- Stack: php + mysql, assume mail() function works.

## Pages

### Install - install.php
- Can only be visited once per environment (localhost / production)
- Sets up database and env.php

### Home - index.php
- General introduction about project.
- CTA to apply for sticker with a neighbor

### Form - form.php
- Form to apply for sticker
    - Form-group 1: Application (3 radio-inputs for various checks)
    - Form-group 2: Neighbor 1 (Name, email, approve for follow-up)
    - Form-group 3: Neighbor 2 (Name, email, approve for follow-up)
    - Form-group 4: Address (zipcode, housenumber, addition)
- Submitting form:
    - create entry in table applications
    - don't check for duplicate addresses or neighbors, just create new entries
    - each neighbor receives email to confirm their application: 'mail 1 - confirm'

### Confirm - confirm.php
- GET payload contains
    - application_id (encrypted to avoid spoofing)
    - neighbor_id (encrypted to avoid spoofing)
- Update 'Neighbor...Approval' in database (meaning that neighbor_id approves application_id)
- If the other neighbor already approved the application, send 'Mail 3: Approved'
    - both neighbors receive mail that application as completed
- If the other neighbor objected to the application, send 'Mail 2: Objection'
    - both neighbors receive mail that application could not be completed

### Objection - objection.php
- GET payload contains
    - application_id (encrypted to avoid spoofing)
    - neighbor_id (encrypted to avoid spoofing)
- Update 'Neighbor...Approval' in database (meaning that neighbor_id objects to application_id)
- Prompt user to provide some feedback outlining their objection
    Ask if this feedback can be shared with the other neighbor.
- If feedback was provided, update NeighborOneFeedback / NeighborTwoFeedback.
    - if feedback can not be shared with other neighbor, wrap the feedback in [private] [/private] tags.
- If the other neighbor already approved or objected to the application, send 'Mail 2: Objection'
    - both neighbors receive mail that application could not be completed

### About - about.php
- Plain text about the project 

### Contact - contact.php
- Contact form with 'name, email, message'
- Forward mail to 'info@marcelschouwenaar.nl'
- Send confirmation email to sender with their own message in body

### Includes
- env.php
    - settings for database connection
- db.php
    - setup database connection
- nav.php
    - responsive for desktop and mobile
    - current page is highlighted in the menu
- head.php
    - included in head
    - includes styles, meta-tags, og-tags, etc.
- footer.php
    - plain text
    - site map

### Other
- gitignore for PHP

## Mail 1: Confirm
- each of the neighbors receives a personal version of this email
- the email has 2 links:
    - Approve --> confirm.php
    - Object --> object.php
- Ensure each link is unique to each neighbor and application

## Mail 2: Objection
- both of the applications receive this email
- email outlines there was no agreement between neighbors. 
- if either neighbor provided feedback, and it's not wrapped in [private] [/private] tags, include in body

## Mail 3: Approved
- both of the applications receive this email
- email explains that sticker will be sent asap