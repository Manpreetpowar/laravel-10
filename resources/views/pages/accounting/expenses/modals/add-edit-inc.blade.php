@csrf
<div class="form-row">

    <div class="form-group col-md-6">
        <label for="expense_name">Expense Name</label>
        <input type="text" class="form-control" id="expense_name" name="expense_name" placeholder="Expense Name" value="{{$expense->expense_name ?? ''}}">
    </div>

    <div class="form-group col-md-6">
        <label for="category_select">Category</label>
        <select id="category" name="category" class="form-control select2-basic-with-search">
                <option value="" selected disabled>Select Category</option>
                @foreach(expenseCategories() as $category)
                <option value="{{$category}}" {{ isset($expense) ? runtimePreselected($category, $expense->category) : ''}}>{{$category}}</option>
                @endforeach
            </select>
    </div>

    <div class="form-group col-md-6">
        <label for="amount">Amount</label>
        <input type="number" min="0" class="form-control" id="amount" name="amount" placeholder="Amount" value="{{$expense->amount ?? ''}}">
    </div>

    <div class="form-group col-md-6">
        <label for="remarks">Remarks</label>
        <input type="text" class="form-control" id="remark" name="remarks" placeholder="Remarks" value="{{$expense->remarks ?? ''}}">
    </div>

    <div class="form-group col-md-6">
        <label for="attachment">Attachment</label>
        <input type="file" class="form-control file-upload-ajax" id="attachment" name="attachment" placeholder="Attachment">
    </div>

</div>
