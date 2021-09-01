<div id="userModal" class="modal fade">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">User details</h4>
                <button class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body mod">
                <form id="userForm">
                    {{ csrf_field() }}
                    <input type="hidden" name="myid" id="myid">
                    <input type="text" hidden id="myrole" value="{{ auth()->user()->roles->first()->id }}">
                    <div class="form-group row">
                        <label for="first_name" class="col-md-4 col-form-label text-md-right">{{ __('Fullname') }}</label>
                        <div class="col-md-6">
                            <input id="first_name" type="text" class="form-control" name="first_name" style="color: black;" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('E-Mail Address') }}</label>
                        <div class="col-md-6">
                            <input id="email" type="email" class="form-control" name="email" style="color: black;" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="role" class="col-md-4 col-form-label text-md-right">{{ __('Level') }}</label>
                        <div class="col-md-6">
                            <select name="role" id="role" class="form-control" style="color: black;">
                                <option value="" selected disabled>select level</option>
                                <option value="Agent">Agent</option>
                                <option value="Manager">Manager</option>
                                <option value="Client">Client</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="status" class="col-md-4 col-form-label text-md-right">{{ __('Status') }}</label>
                        <div class="col-md-6">
                            <select name="status" id="status" class="form-control status" style="color: black;">
                                <option value="" selected disabled>select status</option>
                                <option value="1">Active</option>
                                <option value="0">Inactive</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <input type="button" class="btn btn-primary" data-dismiss="modal" value="Close">
                        <input type="submit" id="subBtn" class="btn btn-primary" value="Save">
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>