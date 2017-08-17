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
		$step					= $this->getSettings()->step;
		$field    		= $this->getSettings()->field;
		$value    		= $this->getSettings()->value;

		$element 			= $criteria[$step];

		$element->getContent()->setAttributes(array(
			$field => $value )
		);

		if ($elementType === 'Entry') {
			craft()->entries->saveEntry($element);
		} else if ($elementType === 'User') {
			craft()->users->saveUser($element);
		}

		return true;
	}
}