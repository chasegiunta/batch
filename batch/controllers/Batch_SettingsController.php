<?php
/**
 * Batch plugin for Craft CMS
 *
 * Batch Controller
 *
 * @author    ChaseGiunta
 * @copyright Copyright (c) 2017 ChaseGiunta
 * @link      chasegiunta.com
 * @package   Batch
 * @since     1.0.0
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
    {x
        $plugin = craft()->plugins->getPlugin('batch');

        $this->renderTemplate('batch/settings');
    }
}
