@csrf
<input type="hidden" id="table-id" name="table-id" value="users-table">
<input type="hidden" id="filter-button" name="filter-button" value="userFilter">
<div class="form-row">
    <div class="form-group col-md-6">
      <label for="role">Designation</label>
      <select id="role" name="role" class="form-control filter-input" data-column-index="2">
            <option value="" disabled selected>Select Role</option>
            @foreach(user_roles() as $role)
            <option value="{{$role->slug}}" {{ isset($filter[2]) ? runtimePreselected($filter[2], $role->slug) : ''}}>{{$role->name}}</option>
            @endforeach
      </select>
    </div>
    
    <div class="form-group col-md-6">
      <label for="status">Status</label>
      
      <select id="status" name="status" class="form-control filter-input" data-column-index="6">
            <option value="" disabled selected>Select Status</option>
            <option value="active" {{ isset($filter[6]) ? runtimePreselected($filter[6], 'active') : ''}}>Active</option>
            <option value="inactive" {{ isset($filter[6]) ? runtimePreselected($filter[6], 'inactive') : ''}}>Inactive</option>
      </select>
    </div>
</div>
  