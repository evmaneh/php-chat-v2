# php-chat-v2


# Set up

How I did it:


Go to codesandbox.io/dashboard

Create a Devbox

Search templates

PHP Starter

Download the github files and upload them there.


# Admin account set up


Go to the files

Goto admin.php

replace "admin" with your username

if (!isset($_SESSION['username']) || $_SESSION['username'] !== 'admin') {

Goto main.php

Do the same thing here

if ($_SESSION['username'] === 'admin') {

<?php if ($_SESSION['username'] === 'admin'): ?>

Finally make sure the account is set up correctly in data/users.txt. Here's an example of how it should look
(USERNAME),(SECURED VERSION OF USER PASSWORD),(IP ADDRESS)
