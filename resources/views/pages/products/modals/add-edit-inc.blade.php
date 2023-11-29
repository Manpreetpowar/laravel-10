@csrf
  <div class="form-row">
    <div class="form-group col-md-6">
        <label for="name">Product Name</label>
        <input type="text" class="form-control" id="name" name="name" placeholder="Product Name" value="{{$product->name ?? ''}}" autocomplete="fsd">
    </div>
    <div class="form-group col-md-6">
        <label for="sku_code">Product Code</label>
        <input type="text" class="form-control" id="sku_code" name="sku_code" placeholder="Product Code" @if(!empty($product)) value="{{$product->sku_code ?? '' }}" @else value="{{$product->sku_code ?? ''}}" @endif autocomplete="off">

        {{-- <input type="text" class="form-control" id="sku_code" name="sku_code" placeholder="Product Code"  value="{{$product->sku_code ?? ''}}"  autocomplete="fsd"> --}}
    </div>
    <div class="form-group col-md-6">
        <label for="category_id">Category</label>
        <select id="category_id" name="category_id" class="form-control">
            <option value="" disabled {{ runtimePreselected(isset($product) ? $product->category_id : '', '') }}>Select Category</option>
            @foreach($categories as $category)
            <option value="{{ $category->id }}" {{ runtimePreselected(isset($product) ? $product->category_id : '', $category->id) }}>
                {{ $category->name }}
            </option>
            @endforeach
        </select>
    </div>

    <div class="form-group col-md-6">
        <label for="color_code">Upload Color</label>
        <input type="color" class="form-control" id="color_code" name="color_code" placeholder="Upload Color"  value="{{$product->color_code ?? ''}}"  autocomplete="fsd">
    </div>


    @if(isset($product) && $product->variants->count())
        @foreach ($product->variants as $variant)
            <div class="form-group col-md-6">
                <label for="price-{{ $variant->product_option_type }}_ppf">{{ ucfirst($variant->product_option_type) }} Price Per Foot</label>
                <input type="number" min="0" class="form-control" id="price-{{ $variant->product_option_type }}" name="price[{{ $variant->product_option_type }}]" placeholder="{{ ucfirst($variant->product_option_type) }} Price Per Foot" value="{{ $variant->option_price ?? 0 }}">
            </div>
        @endforeach
    @else
        <div class="form-group col-md-6">
            <label for="standard_ppf">Standard Price Per Foot</label>
            <input type="number" min="0" class="form-control" id="standard_ppf" name='price[standard]' placeholder="Standard Price Per Foot" value="{{$product->standard_ppf ?? ''}}">
        </div>
        <div class="form-group col-md-6">
            <label for="acc_ppf">ACC Price Per Foot</label>
            <input type="number" min="0" class="form-control" id="acc_ppf" name='price[acc]' placeholder="ACC Price Per Foot" value="{{$product->acc_ppf ?? ''}}" >
        </div>

        <div class="form-group col-md-6">
            <label for="hc_ppf">HC Price Per Foot</label>
            <input type="number" min="0" class="form-control" id="hc_ppf" name='price[hc]' placeholder="HC Price Per Foot" value="{{$product->hc_ppf ?? ''}}" >
        </div>

        <div class="form-group col-md-6">
            <label for="tp_ppf">TP Price Per Meter</label>
            <input type="number" min="0" class="form-control" id="tp_ppf" name='price[tp]' placeholder="TP Price Per Meter" value="{{$product->tp_ppf ?? ''}}" >
        </div>
    @endif

    <div class="form-group col-md-6">
        <label for="is_color_matching_label">Enable Color-Match?</label>
        <div class="form-check m-0 p-0">
            <label for="is_color_matching" class="switch">
                <input class="form-check-input" type="checkbox" id="is_color_matching" name="is_color_matching" value="option1" {{ isset($product) ? runtimePreChecked2($product->is_color_matching,1) : ''}}>
                <span class="slider round"></span>
                <span class="label yes">Yes</span>
                <span class="label no">No</span>
            </label>
        </div>
    </div>

    <div class="form-group col-md-6">
        <label for="document">Image</label>
        <input type="file" class="form-control file-upload-ajax" id="document" name="file" placeholder="Document" value="{{$product->document ?? ''}}" autocomplete="fsd">
    </div>
</div>
