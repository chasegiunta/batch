<?php
/**
 * Batch plugin for Craft CMS
 *
 * Batch Task
 *
 * @author    ChaseGiunta
 * @copyright Copyright (c) 2017 ChaseGiunta
 * @link      chasegiunta.com
 * @package   Batch
 * @since     1.0.0
 */

namespace Craft;

class BatchTask extends BaseTask
{
  /**
   * Defines the settings.
   *
   * @access protected
   * @return array
   */

  protected function defineSettings()
  {
    return array(
      'elementType' => AttributeType::Mixed,
      'section' => AttributeType::Mixed,
      'status' => AttributeType::Mixed,
      'locale' => AttributeType::Mixed,
      'userGroup' => AttributeType::Mixed,

      'field' => AttributeType::Mixed,
      'value' => AttributeType::Mixed,
    );
  }

  /**
   * Returns the default description for this task.
   *
   * @return string
   */
  public function getDescription()
  {
    return "Running Task Dude";
  }

  /**
   * Gets the total number of steps for this task.
   *
   * @return int
   */
  public function getTotalSteps()
  {
    $criteria = $this->getCriteria();
    return count($criteria);
  }

  /**
   * Runs a task step.
   *
   * @param int $step
   * @return bool
   */
  public function runStep($step)
  {
    $criteria     = $this->getCriteria();
    $elementType  = $this->getSettings()->elementType;
    $field        = $this->getSettings()->field;
    $value        = $this->getSettings()->value;

    $description = "Saving $criteria[$step]";
    
    foreach($criteria as $item) {
      return $this->runSubTask('Batch_SubStep', $description, array(
        'criteria'    => $criteria,
        'elementType' => $elementType,
        'step'        => $step,
        'field'       => $field,
        'value'       => $value
      ));
    }
  }

  /**
   * Find out criteria.
   *
   * @param int $step
   * @return bool
   */
  public function getCriteria()
  {
    $elementType  = $this->getSettings()->elementType;
    $section      = $this->getSettings()->section;
    $status       = $this->getSettings()->status;
    $locale       = $this->getSettings()->locale;
    $userGroup    = $this->getSettings()->userGroup;

    if (!empty($elementType)) {
      if ($elementType == 'Entry') {
        $scopeResolution = ElementType::Entry;
      } else if ($elementType == 'User') {
        $scopeResolution = ElementType::User;
      }
      $criteria = craft()->elements->getCriteria($scopeResolution);
    }
    if (!empty($section)) {
      $criteria->section = $section;
    }
    if (!empty($status)) {
      $criteria->status = $status;
    }
    if (!empty($locale)) {
      $criteria->locale = $locale;
    }
    if ($elementType === 'Entry') {
      $criteria->localeEnabled = false;
    } else if ($elementType === 'User') {
      $criteria->group = $userGroup;
    }
    $criteria->limit = null;
    $criteria->find();

    return $criteria;
  }
}
