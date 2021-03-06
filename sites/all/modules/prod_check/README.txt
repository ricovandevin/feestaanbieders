
README file for the Production check & Production monitor Drupal modules.


Introduction
============

When bringing a site live, you should double check a lot of settings, like the
error logging, site e-mail, disabling the Devel module and so on.
Next to that, you should ensure that all SEO modules are installed and properly
configured (like Google Analytics, Page Title, XML Sitemap etc.). The Production
check module will do all of this checking for you and present the results in a
convenient status page accessible through /admin/reports/prod-check. Through
this status page, you can easily navigate to all the settings pages or the
project pages of the missing modules to rectify all you need to.

It would of course also be nice that these settings remain as you set them up.
In some cases, when multiple developers make updates to a live site or with the
odd client having somehow gotten superadmin access, stuff can get changed,
usually unintended. That's where the Production monitor comes in the picture.
You can open up the Production check's XMLRPC interface through its settings
page and have the Production monitor module connect to it from a 'local'
monitoring site in your development environment. This will allow you to monitor
all your sites from a central server and keep an eye on them. When adding a site
using Production monitor, you can indicate what exactly needs to be monitored
for this site. Updates can be requested manually and are fetched automatically
each cron run.

    "But I like Nagios to monitor my sites!"

If you prefer Nagios monitoring, you can open up Production check's Nagios
integration from its settings page. You can specify what exactly you want to
monitor there. You will obviousely need to install the Nagios module to make
this functionality work.


Remote module update status monitoring
======================================
Since Production check recommends to turn of the Update module, we have
integrated its functionality in both Production check and Production monitor.
Production check can be configured to allow to transfer its module list with
versioning information once a week at a given time.
Production monitor can be configured to download this data along with all the
rest. It will then, upon your request (still need to add this on cron, but it's
a heavy operation, thinking about the best way to do this: the boost crawler
code makes a good candidate), check for module updates locally for the remote
site. Production check and Production monitor have the necessary code embedded
so you will never need to activate the Update module, not even on the monitor
site!


Performance monitoring
======================

If you install the performance module on a production site, you can use
Production monitor to remotely monitor the collected performance data. A new
subtab will be available displaying the module data in some nice Google charts.
Be sure to activate the fetching of performance data in the site's config!


Dependencies
============

- Nagios   http://drupal.org/project/nagios

There are no true dependencies defined in the .info file, but naturally you need
to install the Nagios module if you would like to integrate Production check
with your Nagios monitoring setup.

- Performance logging   http://drupal.org/project/performance

Again, no true dependencies defined, but if you want remote performance logging,
this module can provide it for you! Install it on the remote site and enable the
fetching of it's data when adding a site to Production monitor.


Development: hook_prod_check_alter()
====================================

You can implement hook_prod_check_alter() in your own module to add additional
checks or modify the core checks.
The hook receives the default functions divided into 7 categories:

 - settings
 - server
 - performance
 - security
 - modules
 - seo
 - prod_mon
 - perf_data

'prod_mon' & 'perf_data' are special categories that will only be used by the
accompanying Production monitor module.

Your function that implements the actual check must accept 1 string parameter
and return an array using the prod_check_execute_check() function.

An example implementation (note the pass by reference in the hook!):

<?php
/**
 * Implements hook_prod_check_alter()
 * @param array reference to an associative array of all available checks
 */
function my_module_prod_check_alter(&$checks) {
  // Add custom check to the server category:
  //  function_name => title
  // Do not use t() for the title!
  $checks['server']['functions']['my_module_additional_check'] = 'Additional check title';

  // Add custom check for Production Monitor only
  $checks['prod_mon']['functions']['my_module_prod_mon_check'] = 'My Production Monitor only check';

  // Gather performance data
  $checks['perf_data']['functions']['my_module_prod_check_return_data'] = 'Performance logging';

  // Add entirely new category.
  $checks['my_category'] = array(
    'title' => 'Custom category',
    'description' => 'Collection of checks I find important.',
    'functions' => array(
      'my_module_check_stuff' => 'Check stuff',
      'my_module_check_more_stuff' => 'Check more stuff',
    ),
  );
}

/**
 * Custom function to check some things.
 * @param string the caller of the function, defaults to 'internal' but can also
 *        be 'xmlrpc' or 'nagios'
 * @return array you _must_ return prod_check_execute_check($check, $caller) as
 *         per the example below to insure a proper status array is returned.
 */
function my_module_additional_check($caller = 'internal') {
  $check = array();

  $title = 'My modules settings';
  $setting1 = t('Enable debug info');
  $setting2 = t('Disable debug info');
  $path = 'admin/settings/my-module-settings-page';
  if ($caller != 'internal') {
    $path = PRODCHECK_BASEURL . $path;
  }

  $check['my_module_additional_check'] = array(
    '#title' => t($title),
    '#state' => variable_get('my_module_debug', 1) != 1,
    '#severity' => ($caller == 'nagios') ? NAGIOS_STATUS_CRITICAL : REQUIREMENT_ERROR,
    '#value_ok'  => $setting2,
    '#value_nok'  => $setting1,
    '#description_ok'  => prod_check_ok_title($title, $path),
    '#description_nok' => t('Your !link settings are set to %setting1, they should be set to %setting2 on a producion environment!',
      array(
        '!link' => '<em>'.l(t($title), $path, array('attributes' => array('title' => t($title)))).'</em>',
        '%setting1' => $setting1,
        '%setting2' => $setting2,
      )
    ),
    '#nagios_key' => 'MYCHCK',
    '#nagios_type' => 'state',
  );

  return prod_check_execute_check($check, $caller);
}

function my_module_check_stuff($caller = 'internal') {
  [...]

  return prod_check_execute_check($check, $caller);
}

function my_module_check_more_stuff($caller = 'internal') {
  [...]

  return prod_check_execute_check($check, $caller);
}

/**
 * Custom callback for a prod_mon only check. Note the additional parameter in
 * the prod_check_execute_check() call!
 */
function my_module_prod_mon_check($caller = 'internal') {
  [...]

  return prod_check_execute_check($check, $caller, 'prod_mon');
}

/**
 * Return performance data to Production Monitor.
 */
function my_module_prod_check_return_data() {
  $data = my_module_gather_summary_data();

  if (!$data) {
    return array(
      'my_module' => array (
        'title' => 'Performance logging',
        'data' => 'No performance data found.',
       ),
    );
  }

  return array(
    'my_module' => array (
      'title' => 'Performance logging',
      'data' => array(
        'Total number of page accesses' => array($data['total_accesses']),
        'Average duration per page' => array($data['ms_avg'], 'ms'),
        'Average memory per page' => array($data['mb_avg'], 'MB'),
        'Average querycount' => array($data['query_count']),
        'Average duration per query' => array($data['ms_query'], 'ms'),
      ),
    ),
  );
}
?>


Installation
============

Production check
----------------
1. Extract the prod_check module and place it in /sites/all/modules/contrib

2. Remove the 'prod_monitor' folder and all it's contents

3. Upload the prod_check folder to the websites you wish to check / monitor,
 enable the module and adjust it's settings using /admin/config/system/prod-check.

4. You can check the /admin/reports/status page to verify if the Production
 check setup described above was executed correctly and no errors / warnings are
 reported.

5. You can find the result of the Production check module on
 /admin/reports/prod-check

Production monitor
------------------
1. Grab the prod_monitor folder from the package and upload it to your
 'monitoring site' and activate the module.
2. Make sure that the site you wish to monitor is running the prod_check module
3. Navigate to the prod_check settings page and activate XMLRPC and add an API
 key to 'secure' the connection. The key is limited to 128 characters.
4. Add the site to the Production monitor overview page on
 /admin/reports/prod-monitor
5. Enter the url and the API key and hit 'Get settings'. All available checks
 are now retrieved from the remote site. You can uncheck those that you do not
 wish to monitor.
6. If you wish to fetch the data immediately, check the appropriate box and save
 the settings. Good to go!

Upgrading
---------
When upgrading Production monitor to a newer version, always run update.php to
verify if there are database or other updates that need to be applied!
When ignoring this step, you might get errors and/or strange behavior!

Nagios
------
1. Download and install the Nagios module from http://drupal.org/project/nagios
 as per its readme instructions
2. Enable Nagios support in the prod_check module on /admin/settings/prod-check
 by ticking the appropriate box.
3. Untick the checboxes for those items you do not whish to be monitored by
 Nagios.
4. Save the settings and you're good to go!

Performance logging
-------------------
1. Download and install the Nagios module from http://drupal.org/project/performance
 as per its readme instructions
2. Enable fetching of performance data on /admin/reports/prod-monitor when
 adding or editing a site.

Drush
-----
You can view the Production Check statuspage using Drush, simply by using this
command:

  $ drush prod-check

or its alias:

  $ drush pchk

A colour coded table will be printed. The information is limited to the name of
the check and the status. In the Drupal version of the status page, you have an
extra line explaining more about the curent status of a specific check.

For Production monitor, these commands are available:

  $ drush prod-monitor [id]
  $ drush prod-monitor-fetch [id]
  $ drush prod-monitor-flush [id]
  $ drush prod-monitor-delete [id]
  $ drush prod-monitor-updates [id] (--check)

or their aliases:

  $ drush pmon [id]
  $ drush pmon-fe [id]
  $ drush pmon-fl [id]
  $ drush pmon-rm [id]
  $ drush pmon-up [id] (--check)

The id parameter is optional for the prod-monitor command. The best usage is to
first get a list of sites:

  $ drush pmon

Now look up the id of a site, then use the other commands to act on that
specific site by passing it the id:

  $ drush pmon 3
  $ drush pmon-fl 3

You can pass multiple ID's by separating them with spaces:

  $ drush pmon 3 6 19
  $ drush pmon-fl 19 4 1

The prod-monitor-updates command acts on one id only!

APC
---
Production Check complains about APC not being installed or misconfigured. What
is APC you wonder? Well, APC is an opcode caching mechanism that will pre-com-
pile PHP files and keep them stored in memory. The full manual can be found
here: http://php.net/manual/en/book.apc.php .
For Drupal sites, it is important to tune APC in order to achieve maximum per-
formance there. Drupal uses a massive amount of files and therefore you should
assign a proper amount of RAM to APC. For a dedicated setup 64Mb should be
sufficient, in shared setups, you should easily double that!
To tune your setup, you can use the aforementioned hidden link provided by
Production check. You can see the memory usage there, verify your settings and
much more.
To help you out even further, an APC config file can be found in
docs/apc.ini.txt. You must obviousely rename this file and omit the .txt
extension (drupal.org CVS did not seem to accept files with .ini extension?).

Note: This 'hidden link' makes use of the APC supplied PHP code and is subject
to the PHP license: http://www.php.net/license/3_01.txt .


Updates
=======
When new checks are added to the prod_check module, the prod_monitor module will
automatically fetch them from the remote server when you edit the settings. Upon
displaying the edit form, XMLRPC is ALWAYS used to build op the checkboxes array
so that you always have the latest options available.
Cron is NOT used to do this, since we want to keep the transfer to a minimum.


Hidden link
===========
Production check adds a 'hidden link' to the site where you can check the APC
status of your site. This page can be found on /admin/reports/status/apc.
This is in analogy with the system module that adds these 'hidden pages':
 /admin/reports/status/php
 /admin/reports/status/sql

Truely unmissable when setting up your site on a production server to check if
all is well!


The detailed report page
========================

The page is divided into 4 sections:

 - Settings: checks various Drupal settings
 - Server: checks that are 'outside of Drupal' such as APC and wether or not you
           have removed the release note files from the root.
 - Performance: checks relevant to the performance settings in Drupal such as
                page / block caching.
 - Modules: checks if certain modules are on / off
 - SEO: performs very basic SEO checks such as 'is Google Analytics activated
        and did you provide a GA account number.

The sections might shift over time (maybe some stuff should go under a
'Security' section etc.).

The checks itself should be self explanatory to Drupal developers, so they won't
be described in detail here.


Support
=======

For support requests, bug reports, and feature requests, please us the issue cue
of Menu Clone on http://drupal.org/project/issues/prod_check.


Thanks
======

kbahey (http://drupal.org/user/4063) for making the performance logging
integration possible!
bocaj (http://drupal.org/user/582042) for all the great contributions!
