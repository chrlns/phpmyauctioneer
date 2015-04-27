phpMyAuctioneer
---------------

This system was created in the first year of my studies at the University of
Applied Sciences in Osnabrück as a homework (a few years ago).
It works but it is not production ready from a security perspective. Work must
be done on the SQL injection problems.

The system itself produces XML code using PHP classes which are rendered by the
browser using XSL transformations.
The code is sowewhat hacked as it embedds most strings within the code. I'll fix
that if I find some time...

phpMyAuctioneer can be used and redistributed under the terms of the AGPL v3.
If you have questions, suggestions, patches, etc. write to christian(at)lins.me

SETUP
-----

Copy files below application/ to your webserver. Make sure that index.php is
executable.

Create phpMyAuctioneer tables in your MySQL database using database/mysql.sql.

Copy db.inc.sample to db.inc and change the database access settings to fit
your database setup. Make sure that db.inc is NOT served by your webserver.

To make something useful, add auction categories by inserting rows to the
phpmyauctioneer_category table. You should also add menu entries in
phpmyauctioneer_menu table.

WARNING
-------
phpMyAuctioneer is not production usable as it contains severe security issues.
Please report or fix them.
