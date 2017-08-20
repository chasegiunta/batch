<?php
namespace Craft;

/**
 * SubStep task
 */
class Batch_SubStepTask extends BaseTask
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
			'criteria' 		=> AttributeType::Mixed,
			'step' 				=> AttributeType::Mixed,
			'elementType' => AttributeType::Mixed,
			'action'      => AttributeType::Mixed,
			'transferTo'  => AttributeType::Mixed,
			'fieldType'		=> AttributeType::Mixed,
			'field'				=> AttributeType::Mixed,
			'value'				=> AttributeType::Mixed,
		);
	}

	/**
	 * Returns the default description for this task.
	 *
	 * @return string
	 */
	public function getDescription()
	{
		return 'Running SubStep';
	}

	/**
	 * Gets the total number of steps for this task.
	 *
	 * @return int
	 */
	public function getTotalSteps()
	{
		return 1;
	}

	/**
	 * Runs a task step.
	 *
	 * @param int $step
	 * @return bool
	 */
	public function runStep($step)
	{
		$criteria 		= $this->getSettings()->criteria;
		$elementType 	= $this->getSettings()->elementType;
		$fieldType		=	$this->getSettings()->fieldType;
		$action				= $this->getSettings()->action;
		$transferTo   = $this->getSettings()->transferTo;
		
		$step					= $this->getSettings()->step;
		$field    		= trim($this->getSettings()->field);
		$value    		= $this->getSettings()->value;

		$element 			= $criteria[$step];

		if ($action == 'setValue') {
			if ($field == 'enabled') {
				$element->enabled = $value;
			} else {

				if ($fieldType == 'Matrix') {
					$fieldModel = craft()->fields->getFieldByHandle($field);
					foreach ($element->$field as $block) {
						craft()->matrix->deleteBlockById($block->id);
					}
				} else {
					$element->getContent()->setAttributes(array(
						$field => $value )
					);
				}

			}

			if ($elementType === 'Entry') {
				craft()->entries->saveEntry($element);
			} else if ($elementType === 'User') {
				craft()->users->saveUser($element);
			}
		} else if ($action == 'delete') {
			if ($elementType === 'Entry') {
				craft()->entries->deleteEntry($element);
			} else if ($elementType === 'User') {
				if(!empty($transferTo)) {
					$userModel = craft()->users->getUserById($transferTo[0]);
					craft()->users->deleteUser($element, $userModel);
				} else {
					craft()->users->deleteUser($element, null);
				}
			}
		}

		return true;
	}
}