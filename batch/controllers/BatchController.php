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
  protected $allowAnonymous = true;

  /**
   * Handle a request going to our plugin's index action URL, e.g.: actions/batch
   */
  public function actionIndex()
  {  
    if (craft()->request->isPostRequest())
    {
      $elementType    = craft()->request->getPost('elementTypeSetting');
      $section        = craft()->request->getPost('sectionSetting');
      $entryType      = craft()->request->getPost('entryTypeSetting');
      $userGroup      = craft()->request->getPost('userGroupSetting');
      $status         = craft()->request->getPost('statusSetting');
      $locale         = craft()->request->getPost('localeSetting');
      $action         = craft()->request->getPost('actionSetting');
      $transferTo     = craft()->request->getPost('transferContentTo');
      $fieldType      = craft()->request->getPost('fieldTypeSetting');
      $field          = craft()->request->getPost('fieldSetting');
      $value          = craft()->request->getPost('valueSetting');

      $description = "Applying Batch Change";
      if (
        craft()->tasks->createTask('Batch', $description, array(
          'elementType'   => $elementType,
          'section'       => $section,
          'entryType'     => $entryType,
          'userGroup'     => $userGroup,
          'status'        => $status,
          'locale'        => $locale,
          'action'        => $action,
          'transferTo'    => $transferTo,
          'fieldType'     => $fieldType,
          'field'         => $field,
          'value'         => $value
        ))
      ) {
        craft()->userSession->setNotice(Craft::t('Batch task created'));
      } else {
        craft()->userSession->setError(Craft::t('Error creating batch task.'));
      }
    }
  }

  public function actionFetchTypes()
  {
    if (craft()->request->isPostRequest())
    {
      $sectionHandle = craft()->request->getPost('section');
      $section = craft()->sections->getSectionByHandle($sectionHandle);

      $types = $section->getEntryTypes();
      $response = [
        'success' => true,
        'types'   => $types,
        'section' => craft()->request->getPost('section')
      ];

      $this->returnJson($response);
    }
  }

  public function actionFetchFields()
  {
    if (craft()->request->isPostRequest())
    {
      $elementType          = craft()->request->getPost('elementType');
      $sectionHandle        = craft()->request->getPost('section');
      $entryTypeHandle      = craft()->request->getPost('entryType');
      $section              = craft()->sections->getSectionByHandle($sectionHandle);     
      $types                = $section->getEntryTypes();

      if ($elementType != 'User') {
        if ($entryTypeHandle == 'null') {
          $fields = $section->getEntryTypes()[0]->getFieldLayout()->getFields();
        } else {
          foreach ($types as $type) {
            if($type->handle == $entryTypeHandle) {
              $fields = $type->getFieldLayout()->getFields();
            }
          }
        }
      } else {
        $currentUser = craft()->userSession->getUser();
        $fields = $currentUser->getFieldLayout()->getFields();
      }

      $fieldsArray = [];
      foreach ($fields as $field) {
        $fieldsArray[] = $field->getField();
      }

      $response = [
        'success' => true,
        'fields' => $fieldsArray
      ];
      $this->returnJson($response);

    }
  }

  public function actionGetFieldType()
  {
    $fieldHandle = craft()->request->getPost('fieldHandle');
    $fieldModel = craft()->fields->getFieldByHandle($fieldHandle);

    if (!empty($fieldModel)) {
      $fieldType = $fieldModel->type;
      $response = [
        'success' => true,
        'fieldType' => $fieldType
      ];
    } else {
      $response = [
        'success' => false
      ];
    }
    $this->returnJson($response);
  }
}