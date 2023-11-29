        <a href="javascript:void(0);" class="col-sm-3 mt-3 edit-add-modal-button js-ajax-ux-request reset-target-modal-form"
            data-toggle="modal" data-target="#commonModal" data-url="{{ route('service-orders.create')}}"
            data-loading-target="commonModalBody" data-modal-title="Add Order"
            data-action-url="{{ route('service-orders.store') }}"
            data-action-method="POST"
            data-action-ajax-class=""
            data-modal-size=""
             
            data-save-button-class="" data-save-button-text="Save" data-close-button-text="Cancel" data-project-progress="0">
            <div class="card card-statistics">
                <div class="card-body btn-primary">
                    <h2>Add Order</h2>
                </div>
            </div>
        </a>

        <a href="{{url('driver/service-orders')}}" class="col-sm-3 mt-3">
            <div class="card card-statistics">
                <div class="card-body btn-primary">
                    <h2>View Order</h2>
                </div>
            </div>
        </a>

        <a href="{{url('driver/service-orders/pending-delivery')}}" class="col-sm-3 mt-3">
            <div class="card card-statistics">
                <div class="card-body btn-primary">
                    <h2>Delivery</h2>
                </div>
            </div>
        </a>
