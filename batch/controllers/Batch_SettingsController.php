<?php
/**
 * @link      https://dukt.net/craft/twitter/
 * @copyright Copyright (c) 2017, Dukt
 * @license   https://dukt.net/craft/twitter/docs/license
 */

namespace Craft;

/**
 * Batch Settings controller
 */
class Batch_SettingsController extends BaseController
{
    // Public Methods
    // =========================================================================

    /**
     * Settings Index
     *
     * @return null
     */
    public function actionIndex()
    {
        craft()->twitter->requireDependencies();

        $plugin = craft()->plugins->getPlugin('batch');

        $this->renderTemplate('batch/settings');
    }
}
