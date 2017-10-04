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

window.timeout = null;

var batchSettings = new Vue({
  el: '#main',
  delimiters: ['${', '}'],
  data: {
    elementType: 'Entry',
    section: '',
    entryType: '',
    entryTypes: {},
    action: 'setValue',
    fields: '',
    field: '',
    fieldType: '',
    value: 1
  },
  created() {
    var e = document.getElementById("sectionSetting");
    var value = e.options[e.selectedIndex].value;
    this.section = value;
    this.fetchTypes();
  },
  computed: {
    showField() {
      if(this.action != 'delete') {
        if(this.section != 'null') {
          if(this.entryType != 'null' || this.entryTypes.length == 1) {
            return true
          }
        }
        if(this.elementType == 'User') {
          return true
        }
      }
    },
    showHandle() {
      if(this.action != 'delete') {
        if(this.entryType == 'null') {
          return true
        }
      }
    }
  },
  methods: {
    fetchTypesAndFields() {
      this.fetchTypes();
      var _this = this;
      setTimeout(function () {
        _this.fetchFields()
      }, 500);
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
          _this.field = response.fields[0].handle;
          if (!response.success) {
            Craft.cp.displayError('Error fetching fields');
          }
        });
      }
    },
    getFieldType() {
      var timeoutValue = (this.entryType != 'null' || this.entryTypes.length == 1) ? 0 : 500;
      clearTimeout(window.timeout);
      var _this = this;
      window.timeout = setTimeout(function () {
        if (_this.field.trim().length > 0) { //ensure we're not checking for empty strings
          var field = document.getElementById('fieldSetting');
          var data = {
            fieldHandle: _this.field
          }
          Craft.postActionRequest('batch/getFieldType', data, function(response) {
            if (response !== null) {
              _this.fieldType = response.fieldType;
              if (!response.success) {
                setTimeout(function () {
                  Craft.cp.displayNotice('Field with name, "'+_this.field+'", doesn\'t exist');
                }, 2000);
              }
            }
          })
        }
      }, timeoutValue);
    },
    fooBar() {
      if(this.elementType != 'User' && this.action != 'delete') {
        document.getElementsByName('transferContentTo')[0].setAttribute("disabled","disabled")
      } else {
        document.getElementsByName('transferContentTo')[0].removeAttribute("disabled")
      }
    }
  
  }
})