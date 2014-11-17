(function() {
  var $;
  if (window.$ == null) {
    window.$ = {};
  }
  $ = window.$;
  return $.hoverintent = function(targetElement) {
    var curEl, target;
    target = document.getElementById(targetElement);
    curEl = void 0;
    document.onmousemove = function(e) {
      e = e || window.event;
      curEl = e.target || e.srcElement;
    };
    target.onmouseover = function(e) {
      setTimeout((function() {
        if (curEl === target) {
          curEl.setAttribute('data-state', 'block');
        } else {
          curEl.setAttribute('data-state', 'none');
        }
      }), 300);
    };
    return target.onmouseout = function() {
      setTimeout((function() {
        if (curEl !== target) {
          curEl.style.display = "none";
        }
      }), 300);
    };
  };
});

//# sourceMappingURL=hoverintent.js.map
