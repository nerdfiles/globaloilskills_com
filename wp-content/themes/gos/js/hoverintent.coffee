(() ->
  window.$ ?= {}
  $ = window.$
  $.hoverintent = (targetElement) ->
    target = document.getElementById(targetElement)
    curEl = undefined
    document.onmousemove = (e) ->
      e = e or window.event
      curEl = e.target or e.srcElement
      return

    target.onmouseover = (e) ->
      setTimeout (->
        if curEl is target
          curEl.setAttribute 'data-state', 'block'
        else
          curEl.setAttribute 'data-state', 'none'
        return
      ), 300
      return

    target.onmouseout = ->
      setTimeout (->
        curEl.style.display = "none"  if curEl isnt target
        return
      ), 300
      return
)
