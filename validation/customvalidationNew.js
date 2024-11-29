// JavaScript Document
function _isValid(sbtn) {
  $("#savetype").val(sbtn);
  $("body").each(function () {
    var e = 0;
    var t = $(this).find("form");
    var n = $(this).find("input, textarea, radio, checkbox, select");
    n.each(function () {
      var t = this.tagName;
      var n = $(this).attr("type");
      if (
        (t == "INPUT" && n == "text") ||
        t == "TEXTAREA" ||
        t == "SELECT" ||
        n == "checkbox"
      ) {
        _getRules($(this));
        if (isError) {
          e++;
          return false;
        }
      }
    });
    if (!e > 0) {
      t.submit();
      return true;
    }
  });
}
function _getRules(e) {
  var t = e.attr("class");
  if (t) {
    var n = t.split(" ");
    _validate(e, n);
  } else {
    isError = false;
  }
}
function _validate(e, t) {
  promptText = "";
  isError = false;
  for (var n = 0; n < t.length; n++) {
    if (t[n] == "required") {
      _required(e);
    }
    if (t[n] == "email") {
      _email(e);
    }
    if (t[n] == "alphanum") {
      _alphanum(e);
    }
    if (t[n] == "noonlydigit") {
      _noonlydigit(e);
    }
    if (t[n] == "phone") {
      _phone(e);
    }
    if (t[n] == "mobile") {
      _mobile(e);
    }
    if (t[n] == "check_box") {
      _check_box(e);
    }
    if (t[n] == "number") {
      _number(e);
    }
    if (t[n] == "nozero") {
      _nozero(e);
    }
  }
  if (isError) {
    _buildPrompt(e, promptText);
    _addErrorClasses(e);
  } else {
    _removePrompt(e);
    _removeErrorClasses(e);
  }
}
function _required(e) {
  if (!e.val()) {
    isError = true;
    promptText = promptText + "This field is required <br />";
  }
}
function _nozero(e) {
  if (e.val() <= 0) {
    isError = true;
    promptText = promptText + "Please enter valid number <br />";
  }
}
function _email(e) {
  if (e.val() != "") {
    var t = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
    if (!t.test(e.val())) {
      isError = true;
      promptText = promptText + "Please provide a valid email address<br />";
    }
  }
}
function _alphanum(e) {
  var t = /^[a-zA-Z0-9& ._-]+$/;
  if (!t.test(e.val())) {
    isError = true;
    promptText =
      promptText + "This field accepts alphanumeric characters only.<br />";
  }
}
function _noonlydigit(e) {
  var t = /^(\d*[a-zA-Z& ._-]\d*)+$/;
  if (!t.test(e.val())) {
    isError = true;
    promptText = promptText + "Please enter a valid name.<br />";
  }
}
function _phone(e) {
  if (e.val() != "") {
    var t = /^\+{0,1}[0-9,/ \(\)\.\-]+$/;
    if (!t.test(e.val())) {
      isError = true;
      promptText = promptText + "Please enter a valid Phone.<br />";
    }
  }
}
function _mobile(e) {
  var t = /^([0-9]{10,11})+$/;
  if (!t.test(e.val())) {
    isError = true;
    promptText = promptText + "Please enter a valid Mobile Number.<br />";
  }
}
function _number(e) {
  var t = /^[-+]?\d*\.?\d+$/;
  if (!t.test(e.val())) {
    isError = true;
    promptText = promptText + "Please enter a valid Number.<br />";
  }
}
function _check_box(e) {
  var t = false;
  $('input[type="checkbox"]').each(function () {
    if (this.checked) {
      t = true;
    }
  });
  if (!t) {
    isError = true;
    promptText = promptText + "Please check at least 1 box.<br />";
  }
}
function _buildPrompt(e, t) {
  _removePrompt(e);
  var n = $("<div></div>");
  $(n).addClass("formError");
  $(n).addClass("formError" + $(e).attr("name"));
  $("#divCheckbox").after(n);
  var r = $("<div></div>");
  $(r).addClass("formErrorContent");
  $(n).append(r);
  $(r).html(promptText);
  var i = $("<div></div>");
  $(i).addClass("formErrorClose");
  $(r).append(i);
  $(i).html("x");
  var ckclass = e.attr("class");
  var newdiv = "";
  //alert("here");
  if (ckclass) {
    var classes = ckclass.split(" ");
  }
  for (var i = 0; i < classes.length; i++) {
    if (classes[i] == "chosen-select") {
      newdiv = e.attr("name") + "_chzn";
    }
  }
  if (newdiv == "") {
    var s = $(e).offset().top;
    var o = $(e).offset().left;
  } else {
    var s = $("#" + newdiv).offset().top;
    var o = $("#" + newdiv).offset().left;
  }
  var u = $(r).height() + 20;
  var a = $(e).width() - 20; //var f=s-$(window).scrollTop();var l=o-$(window).scrollLeft();
  var f = s;
  var l = o;
  $(n).css("z-index", "1200");
  $(n).css("top", parseFloat(f - u) + "px");
  $(n).css("left", parseFloat(l + a) + "px");
  $(e).focus();
  $(n).css({ opacity: 0, position: "absolute" });
  $(r).css({ padding: "5px" });
  return $(n).animate({ opacity: 0.8 });
}
function _buildAjaxPrompts() {
  var e = $("<div></div>");
  e.addClass("ajaxError");
  form.after(e);
  var t = $("<div></div>");
  t.addClass("ajaxSuccess");
  form.after(t);
  var n = $("<div>Loading...</div>");
  n.addClass("ajaxLoading");
  form.after(n);
}
function _addErrorClasses(e) {
  $(e).addClass("form-error").siblings().addClass("form-error");
}
function _removeErrorClasses(e) {
  $(e).removeClass("form-error").siblings().removeClass("form-class");
  $(e).parent().find("span").removeClass("form-error");
}
function _removePrompt(e) {
  $("body")
    .find(".formError" + $(e).attr("name"))
    .remove();
}
