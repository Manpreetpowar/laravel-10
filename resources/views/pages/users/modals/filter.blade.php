@csrf
<input type="hidden" id="table-id" name="table-id" value="users-table">
<input type="hidden" id="filter-button" name="filter-button" value="userFilter">
<div class="form-row">
    <div class="form-group col-md-6">
      <label for="role">Designation</label>
      <select id="role" name="filter_user_role_type" class="form-control yajra-filter-input" data-column-index="2">
            <option value="" disabled selected>Select Role</option>
            @foreach(user_roles() as $role)
            <option value="{{$role->slug}}" {{ isset($filter['filter_user_role_type']) ? runtimePreselected($filter['filter_user_role_type'], $role->slug) : ''}}>{{$role->name}}</option>
            @endforeach
      </select>
    </div>

    <div class="form-group col-md-6">
      <label for="status">Status</label>

      <select id="status" name="filter_user_status" class="form-control yajra-filter-input" data-column-index="6">
            <option value="" disabled selected>Select Status</option>
            <option value="active" {{ isset($filter['filter_user_status']) ? runtimePreselected($filter['filter_user_status'], 'active') : ''}}>Active</option>
            <option value="inactive" {{ isset($filter['filter_user_status']) ? runtimePreselected($filter['filter_user_status'], 'inactive') : ''}}>Inactive</option>
      </select>
    </div>
</div>
