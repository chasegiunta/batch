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
  public $criteria;
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
      'section'     => AttributeType::Mixed,
      'entryType'   => AttributeType::Mixed,
      'status'      => AttributeType::Mixed,
      'locale'      => AttributeType::Mixed,
      'action'      => AttributeType::Mixed,
      'offset'      => AttributeType::Mixed,
      'transferTo'  => AttributeType::Mixed,
      'fieldType'   => AttributeType::Mixed,
      'userGroup'   => AttributeType::Mixed,

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
    $criteria     = $this->criteria;
    $elementType  = $this->getSettings()->elementType;
    $fieldType    = $this->getSettings()->fieldType;
    $action       = $this->getSettings()->action;
    $transferTo   = $this->getSettings()->transferTo;

    $field        = $this->getSettings()->field;
    $value        = $this->getSettings()->value;

    $element 			= $criteria[$step];

    if ($action == 'delete') {
      $description = "Deleting $criteria[$step]";
    } else {
      $description = "Saving $criteria[$step]";
    }

    if ($action == 'setValue') {
			if ($field == 'enabled') {
				$element->enabled = $value;
			} else {

				if ($fieldType == 'Matrix') {
					$fieldModel = craft()->fields->getFieldByHandle($field);
					foreach ($element->$field as $block) {
            if (isset($block)){
							craft()->elements->deleteElementById($block->id);
						}
					}
				} else {
					$element->getContent()->setAttributes(array(
						$field => $value )
					);
				}

			}

			if ($elementType === 'Entry') {
				craft()->entries->saveEntry($element);
        return true;
			} else if ($elementType === 'User') {
        craft()->users->saveUser($element);
        return true;
			}
    } 
    
    if ($action == 'delete') {
			if ($elementType === 'Entry') {
				craft()->entries->deleteEntry($element);
        return true;
			} else if ($elementType === 'User') {
				if(!empty($transferTo)) {
					$userModel = craft()->users->getUserById($transferTo[0]);
					craft()->users->deleteUser($element, $userModel);
          return true;
				} else {
          craft()->users->deleteUser($element, null);
          return true;
        }
      }
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
    $status       = $this->getSettings()->status;
    $offset       = $this->getSettings()->offset;

    if (empty($elementType)) {
      return false;
    }

    if ($elementType == 'Entry') {

      $section      = $this->getSettings()->section;
      $entryType    = $this->getSettings()->entryType;
      $locale       = $this->getSettings()->locale;

      $scopeResolution = ElementType::Entry;
      $criteria = craft()->elements->getCriteria($scopeResolution);
      if (!empty($section)) {
        $criteria->section = $section;
      }
      if (!empty($entryType) && 'null' != $entryType) {
        $criteria->type = $entryType;
      }
      if (!empty($locale)) {
        $criteria->locale = $locale;
        $criteria->localeEnabled = false;
      }

    } else if ($elementType == 'User') {

      $userGroup    = $this->getSettings()->userGroup;

      $scopeResolution = ElementType::User;
      $criteria = craft()->elements->getCriteria($scopeResolution);
      $criteria->group = $userGroup;

    }

    if (!empty($status)) {
      $criteria->status = $status == 'null' ? null : $status;
    }

    $criteria->offset = isset($offset) ? $offset : 0;

    $criteria->limit = null;
    $criteria->find();

    // Set Criteria on class var
    $this->criteria = $criteria;
    return $criteria;
  }
}
