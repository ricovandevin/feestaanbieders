<?php

/**
 * @file
 * Default theme implementation to display the basic html structure of a single
 * Drupal page.
 *
 * Variables:
 * - $css: An array of CSS files for the current page.
 * - $language: (object) The language the site is being displayed in.
 *   $language->language contains its textual representation.
 *   $language->dir contains the language direction. It will either be 'ltr' or 'rtl'.
 * - $rdf_namespaces: All the RDF namespace prefixes used in the HTML document.
 * - $grddl_profile: A GRDDL profile allowing agents to extract the RDF data.
 * - $head_title: A modified version of the page title, for use in the TITLE
 *   tag.
 * - $head_title_array: (array) An associative array containing the string parts
 *   that were used to generate the $head_title variable, already prepared to be
 *   output as TITLE tag. The key/value pairs may contain one or more of the
 *   following, depending on conditions:
 *   - title: The title of the current page, if any.
 *   - name: The name of the site.
 *   - slogan: The slogan of the site, if any, and if there is no title.
 * - $head: Markup for the HEAD section (including meta tags, keyword tags, and
 *   so on).
 * - $styles: Style tags necessary to import all CSS files for the page.
 * - $scripts: Script tags necessary to load the JavaScript files and settings
 *   for the page.
 * - $page_top: Initial markup from any modules that have altered the
 *   page. This variable should always be output first, before all other dynamic
 *   content.
 * - $page: The rendered page content.
 * - $page_bottom: Final closing markup from any modules that have altered the
 *   page. This variable should always be output last, after all other dynamic
 *   content.
 * - $classes String of classes that can be used to style contextually through
 *   CSS.
 *
 * @see template_preprocess()
 * @see template_preprocess_html()
 * @see template_process()
 */
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML+RDFa 1.0//EN"
  "http://www.w3.org/MarkUp/DTD/xhtml-rdfa-1.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php print $language->language; ?>" version="XHTML+RDFa 1.0" dir="<?php print $language->dir; ?>"<?php print $rdf_namespaces; ?>>

<head profile="<?php print $grddl_profile; ?>">
  <?php print $head; ?>
  <title><?php print $head_title; ?></title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <?php print $styles; ?>
    <!-- Begin Cookie Consent plugin by Silktide - http://silktide.com/cookieconsent -->
    <link rel="stylesheet" type="text/css" href="/cookieconsent/style.min.css"/>
    <script type="text/javascript" src="http://assets.cookieconsent.silktide.com/1.0.8/plugin.min.js"></script>
    <script type="text/javascript">
    // <![CDATA[
    cc.initialise({
      cookies: {
        social: {},
        analytics: {}
      },
      settings: {
        style: "light",
        tagPosition: "vertical-right",
        refreshOnConsent: true,
        disableallsites: true,
        hideprivacysettingstab: true,
        bannerPosition: "top"
      },
      strings: {
        socialDefaultDescription: 'Facebook, Twitter en andere social media websites moeten weten wie u bent om goed te kunnen functioneren.',
        analyticsDefaultDescription: 'We meten anoniem uw gebruik van onze website om de gebruikerservaring te kunnen verbeteren.',
        closeWindow: 'Venster sluiten',
        learnMore: 'Lees meer',
        notificationTitle: 'Uw gebruikerservaring op deze website wordt verhoogd wanneer u cookies accepteert',
        notificationTitleImplicit: 'We gebruiken cookies om u de beste gebruikerservaring op deze website te geven',
        seeDetails: 'zie details',
        seeDetailsImplicit: 'pas uw instellingen aan',
        hideDetails: 'details verbergen',
        allowCookies: 'Cookies toestaan',
        allowCookiesImplicit: 'Sluiten',
        allowForAllSites: 'Toestaan voor alle websites',
        savePreference: 'Voorkeuren opslaan',
        saveForAllSites: 'Opslaan voor alle websites',
        privacySettings: 'Privacyinstellingen',
        privacySettingsDialogTitleA: 'Privacyinstellingen',
        privacySettingsDialogTitleB: 'voor deze website',
        privacySettingsDialogSubtitle: 'Sommige onderdelen van deze website hebben uw toestemming nodig om te weten wie u bent.',
        changeForAllSitesLink: 'Pas instellingen aan voor alle websites',
        preferenceUseGlobal: 'Gebruik algemene instellingen',
        preferenceConsent: 'Toestaan',
        preferenceDecline: 'Weigeren',
        notUsingCookies: 'Deze website gebruikt geen cookies.',
        allSitesSettingsDialogTitleA: 'Privacyinstellingen',
        allSitesSettingsDialogTitleB: 'voor alle websites',
        allSitesSettingsDialogSubtitle: 'U kunt deze cookies toestaan voor alle websites die deze plugin gebruiken.',
        backToSiteSettings: 'Terug naar de website-specifieke instellingen',
        preferenceAsk: 'Vraag mij elke keer',
        preferenceAlways: 'Altijd toestaan',
        preferenceNever: 'Nooit toestaan',
        advertisingDefaultTitle: 'Advertenties',
        advertisingDefaultDescription: 'Advertenties worden automatisch voor u gekozen op basis van uw gedrag en interesses in het verleden.',
        necessaryDefaultTitle: 'Hoogst noodzakelijk',
        necessaryDefaultDescription: 'Sommige cookies op deze website zijn hoogst noodzakelijk voor het functioneren en kunnen niet worden geweigerd.'
      }
    });
    // ]]>
    </script>
    <!-- End Cookie Consent plugin -->
  <?php print $scripts; ?>
    <script type="text/javascript">
        jQuery(window).load(function(){
            (function(){
                var _updateNav = Lightbox.updateNav;

                Lightbox.updateNav = function(){
                    jQuery("#lightbox").trigger('lightbox.updateNav');
                    _updateNav.apply(this, arguments);
                }
            })();
            
            jQuery(document).delegate('#lightbox', 'lightbox.updateNav', function(){
                Drupal.attachBehaviors();
                addthis.toolbox("#addthis-toolbox");
            });
        });
    </script>
<!--[if lt IE 9]>
	<script src="http://css3-mediaqueries-js.googlecode.com/svn/trunk/css3-mediaqueries.js"></script>
<![endif]-->
</head>
<body class="<?php print $classes; ?><?php if(strpos($classes, 'not-front') === false) print ' sidebar-second'; ?>" <?php print $attributes;?>>
  <div id="skip-link">
    <a href="#main-content" class="element-invisible element-focusable"><?php print t('Skip to main content'); ?></a>
  </div>
  <?php print $page_top; ?>
  <?php print $page; ?>
  <?php print $page_bottom; ?>
</body>
</html>
