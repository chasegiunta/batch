/**
 * Batch plugin for Craft CMS
 *
 * Batch JS
 *
 * @author    ChaseGiunta
 * @copyright Copyright (c) 2017 ChaseGiunta
 * @link      chasegiunta.com
 * @package   Batch
 * @since     1.0.0
 */

var batchSettings = new Vue({
  el: '#main',
  delimiters: ['${', '}'],
  data: {
    elementType: 'Entry',
    section: '',
    entryType: '',
    entryTypes: {},
    fields: ''
  },
  created() {
    var e = document.getElementById("sectionSetting");
    var value = e.options[e.selectedIndex].value;
    this.section = value;
    this.fetchTypes();
  },
  methods: {
    fetchTypesAndFields() {
      this.fetchTypes();
      this.fetchFields();
    },
    fetchTypes() {

      var data = {
        section: this.section
      };
      var _this = this;
      Craft.postActionRequest('batch/fetchTypes', data, function(response) {
        _this.entryTypes = response.types;
        _this.entryType = 'null';

        if (!response.success) {
          Craft.cp.displayError('Error fetching entry types');
        }
      });

    },
    fetchFields() {
      if (((this.entryType != 'null' || this.entryTypes.length == 1) && this.section != 'null') || this.elementType == 'User') {
        var data = {
          section: this.section,
          entryType: this.entryType,
          elementType: this.elementType
        };
        var _this = this;
        Craft.postActionRequest('batch/fetchFields', data, function(response) {
          _this.fields = response.fields;
          if (!response.success) {
            Craft.cp.displayError('Error fetching fields');
          }
        });
      }
    }
  }
})