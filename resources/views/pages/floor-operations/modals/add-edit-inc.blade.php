@csrf
  <div class="form-row">
    <div class="form-group col-md-6">
        <label for="username">Username</label>
        <input type="text" class="form-control" id="username" name="username" placeholder="Username" value="{{$user->username ?? ''}}" autocomplete="fsd">
    </div>

    <div class="form-group col-md-6">
        <label for="password">Password</label>
        <input type="password" class="form-control" id="password" name="password" placeholder="Password"  value="">
    </div>

    <div class="form-group col-md-6">
        <label for="name">Name</label>
        <input type="text" class="form-control" id="name" name="name" placeholder="Name"  value="{{$user->name ?? ''}}">
    </div>

    <div class="form-group col-md-6">
      <label for="role">Designation</label>
      <select id="role" name="role" class="form-control user-roles-select">
            <option value="" disabled selected>Select Role</option>
            @foreach(user_roles() as $role)
            <option value="{{$role->slug}}" {{ isset($user) ? runtimePreselected($user->roles->first()->slug, $role->slug) : ''}}>{{$role->name}}</option>
            @endforeach
      </select>
    </div>

    <div class="form-group col-md-6">
        <label for="phone">Contact Number</label>
        <input type="text" class="form-control" id="phone" name="phone" placeholder="Contact Number" value="{{$user->profile->phone ?? ''}}">
    </div>
    <div class="form-group col-md-6">
        <label for="email">Email</label>
        <input type="email" class="form-control" id="email" name="email" placeholder="Email" value="{{$user->email ?? ''}}">
    </div>

    <div class="form-group col-md-6">
    <label for="status">Status</label>
    <select id="status" name="status" class="form-control">
            <option value="active" {{ isset($user) ? runtimePreselected($user->status, 'active') : ''}}>Active</option>
            <option value="inactive" {{ isset($user) ? runtimePreselected($user->status, 'inactive') : ''}}>Inactive</option>
    </select>
    </div>

    <div class="form-group col-md-6">
        <label for="em_contact_name">Emergency Contact Name</label>
        <input type="text" class="form-control" id="em_contact_name" name="em_contact_name" value="{{$user->profile->em_contact_name ?? ''}}" placeholder="Emergency Contact Name">
    </div>

    <div class="form-group col-md-6">
        <label for="em_contact_number">Emergency Contact Number</label>
        <input type="text" class="form-control" id="em_contact_number" name="em_contact_number" value="{{$user->profile->em_contact_number ?? ''}}" placeholder="Contact Number">
    </div>

    <div class="form-group col-md-6 vehicle-input" style="display:{{@$user->roles->first()->slug == 'driver' ? 'block' : 'none'}};">
        <label for="driver_vehicle_number">Driver Vehicle Number</label>
        <input type="text" class="form-control" id="driver_vehicle_number" name="driver_vehicle_number" value="{{$user->profile->driver_vehicle_number ?? ''}}" placeholder="Driver Vehicle Number">
    </div>
</div>
