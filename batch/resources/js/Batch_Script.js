/**
 * Batch plugin for Craft CMS
 *
 * Batch JS
 *
 * @author    ChaseGiunta
 * @copyright Copyright (c) 2017 ChaseGiunta
 * @link      google.com
 * @package   Batch
 * @since     1.0.0
 */

var batchSettings = new Vue({
  el: '#main',
  delimiters: ['${', '}'],
  data: {
    elementType: 'Entry'
  }
})