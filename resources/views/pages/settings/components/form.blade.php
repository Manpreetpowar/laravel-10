<form action="{{ route('setting.update') }}" method="POST" id="pageForm">
    @csrf
    <div class="form-row">
        <div class="form-group col-md-3">
            <label for="gst_per">GST Percentage</label>
            <input type="number" min="0" class="form-control gst-percent" id="gst_per" name="settings_gst_percentage" placeholder="GST Percent" value="{{ settings('settings_gst_percentage') }}" autocomplete="off">
        </div>

        <div class="form-group col-md-9">
            <label for="expenses_category">Expenses Categories</label>
            <select id="expenses_category" name="settings_expenses_category" class="select2-basic form-control select2-tags" multiple="multiple">
                @if(settings('settings_expenses_category') != '')
                    @foreach(expenseCategories() as $category)
                        <option value="{{ $category }}" selected>{{ $category }}</option>
                    @endforeach
                @endif
            </select>
        </div>
        <div class="form-group col-md-3">
            <label for="servicing_email">Service Notification Email</label>
            <input type="email"  class="form-control email" id="servicing_email" name="settings_service_notification_email" placeholder="Servicing Notification Email" value="{{ settings('settings_service_notification_email') }}" autocomplete="off">
        </div>
    </div>
</form>

<button type="submit" id="pageSubmitButton"
class="btn btn-rounded-x btn-primary waves-effect text-left" data-url="{{ route('setting.update') }}"  data-loading-target="new-loader" data-loading-class="show"
data-ajax-type="PUT" data-on-start-submit-button="disable" data-form-id="pageForm">Submit</button>
