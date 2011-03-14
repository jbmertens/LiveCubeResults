To configure this script to run from the same codebase for multiple instances of results, several
things should be done:

1) Create a symlink pointing to the base results directory. For example, if the results script
is sitting in /home/var/www/html/results, a symlink /home/var/www/html/demo can point to this
directory.

2) Duplicate the default.info file in this folder and edit its contents appropriately.  The .info
file should have the same name as the symlink (or directory) it is sitting in.  For example, if
the symlink is ~/demo, a new .info file should be named "demo.info".  If no site-specific file is
found, the default.info file will be used.

2) Duplicate the default.info file in the scoreboard folder and edit its contents appropriately.
The .info file should have the same name as the other .info file.