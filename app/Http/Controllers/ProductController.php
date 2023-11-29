<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Repositories\ProductRepository;
use App\Repositories\DestroyRepository;
use App\Repositories\AttachmentRepository;
use App\DataTables\ProductsDataTable;
use Validator, Datatables;

class ProductController extends Controller
{
    protected $productRepo;
    protected $attachmentRepo;

    public function __construct(
        productRepository $productRepo,
        AttachmentRepository $attachmentRepo)
        {
        $this->productRepo = $productRepo;
        $this->attachmentRepo = $attachmentRepo;
        }

    /**
     * Display a listing of the resource.
    */
    public function index(ProductsDataTable $dataTable)
    {
        $page = $this->pageSetting('listing');

        return $dataTable->render('pages.products.wrapper',compact('page'));

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::all();
        $html = view('pages.products.modals.add-edit-inc', compact('categories'))->render();
        $response['dom_html'][] = array(
            'selector' => '#commonModalBody',
            'action' => 'replace',
            'value' => $html);

        //show modal footer
        $response['dom_visibility'][] = array('selector' => '#commonModalFooter', 'action' => 'show');

        $response['postrun_functions'][] = [
            'value' => 'NXCreateUpdateProduct'];

        return response()->json($response,200);
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'sku_code' => ['required','unique:products'],
            'category_id' => ['required'],
        ]);
        validationToaster($validator);

        request()->merge(['sku_code' => request('sku_code'),'is_color_matching' => request()->is_color_matching == 'on' ? 1 : 0]);
        $product = $this->productRepo->create();

        $products = $this->productRepo->get($product->id)->get();

        if(request()->has('attachments')){
            foreach(request('attachments') as $key => $value){
                $data = [
                    'attachment_id' => $key,
                    'attachmentresource_type' => 'product',
                    'attachmentresource_id' => $product->id,
                    'attachment_directory' => $key,
                    'attachment_uniqiueid' => $key,
                    'attachment_filename' => $value,

                ];

                if(!$file = $this->attachmentRepo->process($data)){
                    abort(409, 'Something went wrong');
                }
            }
        }
        $ajax['postrun_functions'][] = [
            'value' => 'NXDatatableReload'];
        //hide modal
        $ajax['dom_visibility'][] = array('selector' => '#commonModal', 'action' => 'close-modal');
        //notice
        $ajax['notification'] = array('type' => 'success', 'value' => 'Request has been completed');

        //ajax response & view
        return response()->json($ajax);
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $products = $this->productRepo->get($id);
        $product = $products->with('variants')->first();
        $categories = Category::all();
        $html = view('pages.products.modals.add-edit-inc', compact('product','categories'))->render();
        $response['dom_html'][] = array(
            'selector' => '#commonModalBody',
            'action' => 'replace',
            'value' => $html);

        //show modal footer
        $response['dom_visibility'][] = array('selector' => '#commonModalFooter', 'action' => 'show');

        $response['postrun_functions'][] = [
            'value' => 'NXCreateUpdateProduct'];

        return response()->json($response,200);
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validator = Validator::make($request->all(), [
            'sku_code' => ['required','unique:products,sku_code,'.$id],
            'category_id' => ['required'],
        ]);
        validationToaster($validator);
        request()->merge(['sku_code' => request('sku_code'),'is_color_matching' => request()->is_color_matching == 'on' ? 1 : 0]);
        $product = $this->productRepo->update($id);

        if(request()->has('attachments')){
            $this->attachmentRepo->clear($product->id,'product');
            foreach(request('attachments') as $key => $value){
                $data = [
                    'attachment_id' => $key,
                    'attachmentresource_type' => 'product',
                    'attachmentresource_id' => $product->id,
                    'attachment_directory' => $key,
                    'attachment_uniqiueid' => $key,
                    'attachment_filename' => $value,

                ];

                if(!$file = $this->attachmentRepo->process($data)){
                    abort(409, 'Something went wrong');
                }
            }
        }

        //show modal footer
        $ajax['dom_visibility'][] = array('selector' => '#commonModal', 'action' => 'close-modal');

        //notice
        $ajax['notification'] = array('type' => 'success', 'value' => 'Request has been completed');

        $ajax['postrun_functions'][] = [
            'value' => 'NXDatatableReload'];

        //ajax response & view
        return response()->json($ajax);
    }

    /**
     * Display the specified resource.
     */
    public function changeColorMatchStatus($id)
    {
        $product = $this->productRepo->get($id);
        if (!$product) {
            //notice
            $ajax['notification'] = array('type' => 'error', 'value' => 'Order not found');

            //ajax response & view
            return response()->json($ajax);
        }

        request()->merge(['is_color_matching' => request('is_color_matching_'.$id) != '' ? 1 : 0]);
        $product = $this->productRepo->update($id);

        //hide modal
        $ajax['dom_visibility'][] = array('selector' => '#commonModal', 'action' => 'close-modal');
        //notice
        $ajax['notification'] = array('type' => 'success', 'value' => 'Request has been completed');
        $ajax['postrun_functions'][] = [
            'value' => 'NXDatatableReload'];

        //ajax response & view
        return response()->json($ajax);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DestroyRepository $destroyRepo, $id)
    {
        $product = $this->productRepo->get($id)->first();

        $destroyRepo->deleteProduct($product->id);

        //hide modal
        $ajax['dom_visibility'][] = array('selector' => '#commonModal', 'action' => 'close-modal');

        //notice
        $ajax['notification'] = array('type' => 'success', 'value' => 'Request has been completed');

        $ajax['postrun_functions'][] = [
            'value' => 'NXDatatableReload'];

        //ajax response & view
        return response()->json($ajax);
    }

     /**
     * Page content.
     */
    public function pageSetting($type = null, $data=null)
    {
        $page = ['pageTitle' =>'Inventory Module - Edgeband Products'];
        return $page;
    }
}
