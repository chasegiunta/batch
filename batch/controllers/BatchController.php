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

class BatchController extends BaseController
{
  /**
   * @var    bool|array Allows anonymous access to this controller's actions.
   * @access protected
   */
  protected $allowAnonymous = array('actionIndex',
      );

  /**
   * Handle a request going to our plugin's index action URL, e.g.: actions/batch
   */
  public function actionIndex()
  {   
    if (craft()->request->isPostRequest())
    {
      $elementType    = craft()->request->getPost('elementTypeSetting');
      $section        = craft()->request->getPost('sectionSetting');
      $userGroup      = craft()->request->getPost('userGroupSetting');
      $status         = craft()->request->getPost('statusSetting');
      $locale         = craft()->request->getPost('localeSetting');
      $field          = craft()->request->getPost('fieldSetting');
      $value          = craft()->request->getPost('valueSetting');

      $description = "Applying Batch Change";
      craft()->tasks->createTask('Batch', $description, array(
        'elementType'   => $elementType,
        'section'       => $section,
        'userGroup'     => $userGroup,
        'status'        => $status,
        'locale'        => $locale,
        'field'         => $field,
        'value'         => $value
      ));
    }
  }
}