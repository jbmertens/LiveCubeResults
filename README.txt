============================
WCA Live Results v2.4 Readme
    Copyright ©2008-2010
============================

Github
-------
This is a copy of the code hosted by Jim at http://koii.cubingusa.com/main/competitions/tools as of Mar 14, 2011. Lucas is uploading it to a github repository to make it easier to work on (and, for example, easier to offer to other people to install).


General
-------

This program is intended to display results read by the WCA scoresheet using flatfile storage.
As of this writing, the WCA scoresheet is available for download here:
http://worldcubeassociation.org/node/621


Credits
-------

Primary Maintainer: Jim Mertens - mertens . 11 at osu . edu
Code Contributed by: Lucas Garron lucasg at gmx . de

Based on the PHP Excel Reader class by Matt Kruse
Documentation at
http://code.google.com/p/php-excel-reader/wiki/Documentation


Installation
------------

To install, simply drag and drop all files into the desired directory.

MAKE SURE you have copied the .htaccess file, otherwise sensitive data
may be accessible in unprotected files.

The admin.php page will alert you as to filesystem writability.  Several
directories and/or files will need to be writable.

The upload password should be changed in ~assets/config/default.info.
Don't use the default!


Customization
-------------

Many basic changes can be made using the admin.php page once installed.

The WCA Competition ID should be the id assigned by the WCA to the competition.  
This id can be found in the URL of the WCA competition results page.  For 
example, if the URL is:
http://www.worldcubeassociation.org/results/c.php?i=USNationals2009
Then the WCA competition id will be USNationals2009.

If you would like to request another feature or option for customization,
please contact Jim at the email above.


Uploading Results
-----------------

Results should be saved as a Microsoft Excel 97-2003 document with any
macros disabled.  Other file formats are probably not compatible.

An automated upload script was made by Lucas Garron.  You will need to find
and contact him for more information about it.

Round specifiers may be placed after event names.  These are the values in
the A1 cell of a particular sheet.  The following specifiers are
searched for in the following order (case insensitive).  Asterisks indicate
wildcards, and [w] indicates a single whole word.
  [Event] Round*
  [Event] [w] Round*
  [Event] Prelim*
  [Event] Final*
  [Event] Qual*
When two rounds with the [EVENT] specifier are found, they are grouped together
into rounds of a single event.  Rounds of events should not have the same name.

Sheets will only be uploaded if data is present in the A1 cell.  Data will only
be read from rows that have information in the A column ("Position"), and only
these rows.  Only columns with data in the 4th row ("header" row) will be read.


Scoreboard Results
------------------

Basic changes to the scoreboard can be made on the administration page (admin.php).
Changes will be pulled out by the scoreboard, so the scoreboard shouldn't be reloaded
or have its display changed manually.


Multisite Configuration
-----------------------
See assets/config/README.txt for more information.


End
---
This readme was last updated Feb. 09 2010.