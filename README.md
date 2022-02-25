# moodle-auth_queryparam

Login using your username and password as query params

Note this plugin has extremely low security and is generally not advised for
normal production use.

To login, simply append the username and password as url params to any normal url:

http://moodle.local/course/view.php?id=2&username=admin&password=admin

If the user exists and isn't already logged in and is of type 'queryparm' then
they should be logged in and continue on their merry way.

