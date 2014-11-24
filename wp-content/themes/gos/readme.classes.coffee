# GOS Front End

###

Make it Moveable REST. 

    @see https://github.com/cliffwoo/rest-api-cheat-sheet/blob/master/REST-API-Cheat-Sheet.md

###

class AddView(FriendView):
    def get(self, request):
        # delete-specific logic here
        # now call AddView get()
        return super(AddView, self).get(request)

class UpdateView(FriendView):
    def get(self, request):
        # delete-specific logic here
        # now call UpdateView get()
        return super(UpdateView, self).get(request)

class DeleteView(FriendView):
    def get(self, request):
        # delete-specific logic here
        # now call DeleteView get()
        return super(DeleteView, self).get(request)

class DeleteView(FriendView):
    def get(self, request):
        # delete-specific logic here
        # now call DeleteView get()
        return super(DeleteView, self).get(request)

class DeleteView(FriendView):
    def get(self, request):
        # delete-specific logic here
        # now call DeleteView get()
        return super(DeleteView, self).get(request)
