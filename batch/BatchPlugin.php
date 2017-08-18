<?php
/**
 * Batch plugin for Craft CMS
 *
 * @author    ChaseGiunta
 * @copyright Copyright (c) 2017 ChaseGiunta
 * @link      chasegiunta.com
 * @package   Batch
 * @since     1.0.0
 */

namespace Craft;

class BatchPlugin extends BasePlugin
{
  /**
   * @return mixed
   */
  public function init()
  {
      parent::init();
  }

  /**
   * Returns the user-facing name.
   *
   * @return mixed
   */
  public function getName()
  {
        return Craft::t('Batch');
  }

  /**
   * Plugins can have descriptions of themselves displayed on the Plugins page by adding a getDescription() method
   * on the primary plugin class:
   *
   * @return mixed
   */
  public function getDescription()
  {
      return Craft::t('Easily batch set field values across users or entries');
  }

  /**
   * Plugins can have links to their documentation on the Plugins page by adding a getDocumentationUrl() method on
   * the primary plugin class:
   *
   * @return string
   */
  public function getDocumentationUrl()
  {
      return 'https://github.com/chasegiunta/batch/blob/master/README.md';
  }

  /**
   * Plugins can now take part in Craft’s update notifications, and display release notes on the Updates page, by
   * providing a JSON feed that describes new releases, and adding a getReleaseFeedUrl() method on the primary
   * plugin class.
   *
   * @return string
   */
  public function getReleaseFeedUrl()
  {
      return 'https://raw.githubusercontent.com/chasegiunta/batch/master/releases.json';
  }

  /**
   * Returns the version number.
   *
   * @return string
   */
  public function getVersion()
  {
      return '1.1.0';
  }

  /**
   * As of Craft 2.5, Craft no longer takes the whole site down every time a plugin’s version number changes, in
   * case there are any new migrations that need to be run. Instead plugins must explicitly tell Craft that they
   * have new migrations by returning a new (higher) schema version number with a getSchemaVersion() method on
   * their primary plugin class:
   *
   * @return string
   */
  public function getSchemaVersion()
  {
      return '1.0.0';
  }

  /**
   * Returns the developer’s name.
   *
   * @return string
   */
  public function getDeveloper()
  {
      return 'Chase Giunta';
  }

  /**
   * Returns the developer’s website URL.
   *
   * @return string
   */
  public function getDeveloperUrl()
  {
      return 'chasegiunta.com';
  }

  /**
   * Returns whether the plugin should get its own tab in the CP header.
   *
   * @return bool
   */
  public function hasCpSection()
  {
      return false;
  }

  /**
   * Called right before your plugin’s row gets stored in the plugins database table, and tables have been created
   * for it based on its records.
   */
  public function onBeforeInstall()
  {
  }

  /**
   * Called right after your plugin’s row has been stored in the plugins database table, and tables have been
   * created for it based on its records.
   */
  public function onAfterInstall()
  {
  }

  /**
   * Called right before your plugin’s record-based tables have been deleted, and its row in the plugins table
   * has been deleted.
   */
  public function onBeforeUninstall()
  {
  }

  /**
   * Called right after your plugin’s record-based tables have been deleted, and its row in the plugins table
   * has been deleted.
   */
  public function onAfterUninstall()
  {
  }

  /**
   * Defines the attributes that model your plugin’s available settings.
   *
   * @return array
   */
  protected function defineSettings()
  {
  }

  /**
   * Hook Register CP Routes
   */
  public function registerCpRoutes()
  {
      return array(
          'batch/settings' => array('action' => "batch/settings/index"),
      );
  }

  /**
   * Get Settings URL
   */
  public function getSettingsUrl()
  {
      return 'batch/settings';
  }

}