((angular, doc, HTML, $) ->
  __ = (obj) ->
    console.log obj

  # @note Use Google $ trs to translate JSON at the presentation layer 
  # description.
  __trs = (obj) ->
    __ obj
      _prepareSaveResult: () ->
        __init = (result) ->
          return result
        result = __init()
        return result
      __construct: () ->
        init = (() ->
            serviceInterface =
              getCompany: () ->
                that = @
                try
                    saveResult = that._prepareSaveResult(saveContext, data)
                    deferred.resolve saveResult
                catch err
                    deferred.reject err
                    errorService.getError err
                return deferred
              wp_get_current_user: () ->
            return serviceInterface
          )()
        return init
    return new obj

  $('input').prop('placeholder', __trs(''))

)(angular, document, HTML, jQuery)
