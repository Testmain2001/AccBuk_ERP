// ==ClosureCompiler==
// @output_file_name default.js
// @compilation_level SIMPLE_OPTIMIZATIONS
// ==/ClosureCompiler==

/*
 * easyValidate, a form validation jquery plugin
 * By Alex Gill, www.alexpetergill.com
 * Version 1.2
 * Copyright 2011 APGDESIGN
 * Updated 19/01/2013
 * Free to use under the MIT License
 * http://www.opensource.org/licenses/mit-license.php
 */

function _isValid() {
  $("body").each(function () {
    var errorsFound = 0;
    var form = $(this).find("form");
    var elements = $(this).find("input, textarea, radio, checkbox, select");
    elements.each(function () {
      var elementTagName = this.tagName;

      var elementType = $(this).attr("type");

      if (
        (elementTagName == "INPUT" && elementType == "text") ||
        elementTagName == "TEXTAREA" ||
        elementTagName == "SELECT" ||
        elementType == "checkbox"
      ) {
        _getRules($(this));
        //alert(isError);
        if (isError) {
          errorsFound++;
          return false;
          //alert(elementType);
        }
      }
    });
    if (!errorsFound > 0) {
      form.submit();
      return true;
    }
  });
}

function _getRules(element) {
  var rulesParsed = element.attr("class");
  if (rulesParsed) {
    var rules = rulesParsed.split(" ");
    //alert(rules);
    _validate(element, rules);
  }
}

// APPLY RULES TO EACH ELEMENT
function _validate(element, rules) {
  // RESET VALUES FOR EACH ELEMENT
  promptText = "";
  isError = false;

  // LOOP RULES FOR EACH ELEMENT
  for (var i = 0; i < rules.length; i++) {
    if (rules[i] == "required") {
      _required(element);
    }
    if (rules[i] == "email") {
      _email(element);
    }
    if (rules[i] == "alphanum") {
      _alphanum(element);
    }
    if (rules[i] == "noonlydigit") {
      _noonlydigit(element);
    }
    if (rules[i] == "phone") {
      _phone(element);
    }
    if (rules[i] == "mobile") {
      _mobile(element);
    }
    if (rules[i] == "check_box") {
      _check_box(element);
    }
    if (rules[i] == "number") {
      _number(element);
    }
    if (rules[i] == "nozero") {
      _nozero(element);
    }
  }

  // BUILD PROMPT IF RULE FAILS
  if (isError) {
    _buildPrompt(element, promptText);
    _addErrorClasses(element);
  } else {
    _removePrompt(element);
    _removeErrorClasses(element);
  }
}

// RULE: REQUIRED FIELD
function _required(element) {
  if (!element.val()) {
    isError = true;
    promptText = promptText + "This field is required <br />";
  }
}

function _nozero(element) {
  if (element.val() <= 0) {
    isError = true;
    promptText = promptText + "Please enter valid number <br />";
  }
}

// RULE: VALID EMAIL STRING REQUIRED
function _email(element) {
  if (element.val() != "") {
    var filter =
      /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
    if (!filter.test(element.val())) {
      isError = true;
      promptText = promptText + "Please provide a valid email address<br />";
    }
  }
}

// RULE: VALID ALPHANUMERIC STRING REQUIRED
function _alphanum(element) {
  var filter = /^[a-zA-Z0-9 ._-]+$/;
  if (!filter.test(element.val())) {
    isError = true;
    promptText =
      promptText + "This field accepts alphanumeric characters only.<br />";
  }
}

function _noonlydigit(element) {
  var filter = /^(\d*[a-zA-Z ._-]\d*)+$/;
  if (!filter.test(element.val())) {
    isError = true;
    promptText = promptText + "Please enter a valid name.<br />";
  }
}

function _phone(element) {
  if (element.val() != "") {
    var filter = /^\+{0,1}[0-9 \(\)\.\-]+$/;
    if (!filter.test(element.val())) {
      isError = true;
      promptText = promptText + "Please enter a valid Phone.<br />";
    }
  }
}

function _mobile(element) {
  var filter = /^([0-9]{10,11})+$/;
  if (!filter.test(element.val())) {
    isError = true;
    promptText = promptText + "Please enter a valid Mobile Number.<br />";
  }
}

function _number(element) {
  var filter = /^[-+]?\d*\.?\d+$/;
  if (!filter.test(element.val())) {
    isError = true;
    promptText = promptText + "Please enter a valid Number.<br />";
  }
}

function _check_box(element) {
  var amIChecked = false;
  $('input[type="checkbox"]').each(function () {
    if (this.checked) {
      amIChecked = true;
    }
  });
  if (!amIChecked) {
    isError = true;
    promptText = promptText + "Please check at least 1 box.<br />";
  }
}

function _buildPrompt(element, prompText) {
  // REMOVE ALL EXISTING PROMPTS ON INIT
  _removePrompt(element);

  // CREATE ERROR WRAPPER
  var divFormError = $("<div></div>");
  $(divFormError).addClass("formError");
  $(divFormError).addClass("formError" + $(element).attr("name"));

  $(".row-fluid").after(divFormError);

  // CREATE ERROR CONTENT
  var formErrorContent = $("<div></div>");
  $(formErrorContent).addClass("formErrorContent");
  $(divFormError).append(formErrorContent);
  $(formErrorContent).html(promptText);

  var formErrorClose = $("<div></div>");
  $(formErrorClose).addClass("formErrorClose");
  $(formErrorContent).append(formErrorClose);
  $(formErrorClose).html("x");

  var eTop = $(element).offset().top;
  var eLeft = $(element).offset().left;
  //$('#test').text();

  //var offset = $(element).position();
  var ht = $(formErrorContent).height() + 20;
  var wt = $(element).width() - 20;
  //alert(wt);
  var pos1 = eTop - $(window).scrollTop();
  var pos2 = eLeft - $(window).scrollLeft();

  $(divFormError).css("z-index", "1200");
  $(divFormError).css("top", parseFloat(pos1 - ht) + "px");
  $(divFormError).css("left", parseFloat(pos2 + wt) + "px");
  $(element).focus();
  // DEFINE LAYOUT WITH CSS
  $(divFormError).css({
    opacity: 0,
    position: "absolute",
  });

  $(formErrorContent).css({
    padding: "5px",
  });

  // SHOW PROMPT
  return $(divFormError).animate({
    opacity: 0.8,
  });
}

// BUILD AJAX PROMPTS
function _buildAjaxPrompts() {
  var ajaxErrorDiv = $("<div></div>");
  ajaxErrorDiv.addClass("ajaxError");
  form.after(ajaxErrorDiv);

  var ajaxSuccessDiv = $("<div></div>");
  ajaxSuccessDiv.addClass("ajaxSuccess");
  form.after(ajaxSuccessDiv);

  var ajaxLoadingDiv = $("<div>Loading...</div>");
  ajaxLoadingDiv.addClass("ajaxLoading");
  form.after(ajaxLoadingDiv);
}

// ADD ERROR CLASSES TO ELEMENTS
function _addErrorClasses(element) {
  $(element).addClass("form-error").siblings().addClass("form-error");
}

// REMOVE ERROR CLASSES FROM ELEMENTS
function _removeErrorClasses(element) {
  $(element).removeClass("form-error").siblings().removeClass("form-class");
  $(element).parent().find("span").removeClass("form-error"); //NOT SURE WHY THIS WASNT REMOVED WITH SIBLINGS()
}
function _removePrompt(element) {
  $("body")
    .find(".formError" + $(element).attr("name"))
    .remove();
}
