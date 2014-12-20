((doc, HTML, $) ->

  __ = (obj) ->
    console.log obj

  user_type_select = () ->
    h = HTML
    form = h.query('.register-form--role')
    registerFormRoleItem = h.query( 'label input' )
    $.each registerFormRoleItem, () ->
      $listItem = $ this
      $listItem.bind 'click', (e) ->
        $listItem.closest('.register-form--role').find('label').removeClass 'active'
        $listItem.closest('label').addClass 'active'

  user_type_select()

  # @note Use Google $ trs to translate JSON at the presentation layer 
  # description.

  #__trs = (obj) ->
    #__ obj
      #_prepareSaveResult: () ->
        #__init = (result) ->
          #return result
        #result = __init()
        #return result
      #__construct: () ->
        #init = (() ->
            #serviceInterface =
              #getCompany: () ->
                #that = @
                #try
                    #saveResult = that._prepareSaveResult(saveContext, data)
                    #deferred.resolve saveResult
                #catch err
                    #deferred.reject err
                    #errorService.getError err
                #return deferred
              #wp_get_current_user: () ->
            #return serviceInterface
          #)()
        #return init
    #return new obj

  #$('#s').prop('placeholder', 'Search Jobs')

)(document, HTML, jQuery)
