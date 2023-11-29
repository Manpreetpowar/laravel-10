<?php

namespace App\Http\Controllers;


use App\Repositories\ExpenseRepository;
use App\Repositories\DestroyRepository;
use App\Repositories\AttachmentRepository;
use Illuminate\Http\Request;
use App\Models\Expense;
use Datatables, Validator;

class ExpenseController extends Controller
{
    protected $expenseRepo;
    protected $attachmentRepo;

    public function __construct(ExpenseRepository $expenseRepo, AttachmentRepository $attachmentRepo){
        $this->expenseRepo = $expenseRepo;
        $this->attachmentRepo = $attachmentRepo;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        $page = $this->pageSetting('listing');

        return view('pages.accounting.expenses.wrapper',compact('page'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function list()
    {
        $expenses = $this->expenseRepo->get();

        return Datatables::eloquent($expenses)
                ->addColumn('action', function($data) {
                    return view('pages.accounting.expenses.components.action',['expense'=>$data]);
                })
                ->editColumn('created_at', function($data) {
                    return runtimeDate($data->created_at);
                })
                ->editColumn('remarks', function($data) {
                    $html = '<span class="d-none">'.$data->remarks.'</span><form id="remarkForm-'.$data->id.'"><input type="hidden" name="_token" value="'.csrf_token().'"><input type="text" class="remark-input form-control" name="remarks" value="'.$data->remarks.'"'
                    .'data-url="'.url('accountings/expenses/update-remark/'.$data->id.'').'" data-ajax-type="post" data-form-id="remarkForm-'.$data->id.'"></form>';
                    return $html;
                })
                ->editColumn('amount', function($data) {
                    return '$'.$data->amount;
                })
                ->editColumn('attachment', function($data) {
                    if($data->attachments){
                      return  '<a class="fancybox preview-image-thumb"
                                href="'.url('storage/files/attachments/'. $data->attachments->attachment_directory .'/'. $data->attachments->attachment_filename)  .'"
                                title="'. str_limit($data->attachments->attachment_filename, 60) .'"
                                alt="'. str_limit($data->attachments->attachment_filename, 60) .'">
                                Attachment
                            </a>';
                    }else{
                        return '';
                    }
                })
                ->rawColumns(['action','remarks','attachment'])
                ->make(true);
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

        $html = view('pages.accounting.expenses.modals.add-edit-inc')->render();
        $response['dom_html'][] = array(
            'selector' => '#commonModalBody',
            'action' => 'replace',
            'value' => $html);

        //show modal footer
        $response['dom_visibility'][] = array('selector' => '#commonModalFooter', 'action' => 'show');

        $response['postrun_functions'][] = [
            'value' => 'NXCreateUpdateExpense'];

        return response()->json($response,200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'attachment' => ['required'],
            'category'   => ['required']
        ]);
        validationToaster($validator);

        $expense_id = generateUniqueID(new Expense, 4);

        request()->merge(['expense_id' => $expense_id]);
        $expense = $this->expenseRepo->create();

        if(request()->has('attachments')){
            foreach(request('attachments') as $key => $value){
                $data = [
                    'attachment_id' => $key,
                    'attachmentresource_type' => 'expense',
                    'attachmentresource_id' => $expense->id,
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
    public function show($expense)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {

        $expense = $this->expenseRepo->get($id)->first();
        $html = view('pages.accounting.expenses.modals.add-edit-inc',compact('expense'))->render();
        $response['dom_html'][] = array(
            'selector' => '#commonModalBody',
            'action' => 'replace',
            'value' => $html);

        //show modal footer
        $response['dom_visibility'][] = array('selector' => '#commonModalFooter', 'action' => 'show');

        $response['postrun_functions'][] = [
            'value' => 'NXCreateUpdateExpense'];

        return response()->json($response,200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request,string $id)
    {
        $validator = Validator::make($request->all(), [
            'category'     => ['required', 'string', 'max:255'],
            'expense_name' => ['required', 'string'],
            'amount'       => ['required'],
            'remarks'      => ['required'],
        ]);
        validationToaster($validator);

        // update the expense record
        $expense = $this->expenseRepo->update($id);

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
     * Update the specified resource in storage.
     */
    public function updateRemark(Request $request, $id)
    {
        $expense = $this->expenseRepo->update($id);
        //notice
        $ajax['notification'] = array('type' => 'success', 'value' => 'Request has been completed');
        //ajax response & view
        return response()->json($ajax);
    }

    public function expensefilter(){

        $filter = [];
        if(request()->has('filter')){
            $filter = json_decode(request('filter'), true);
        }

        $html = view('pages.accounting.expenses.modals.filter', compact('filter'))->render();
        $response['dom_html'][] = array(
            'selector' => '#actionsModalBody',
            'action' => 'replace',
            'value' => $html);

        //show modal footer
        $response['dom_visibility'][] = array('selector' => '#actionsModalFooter', 'action' => 'show');

        $response['postrun_functions'][] = [
            'value' => 'NXFilter'];

        return response()->json($response,200);
    }



    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DestroyRepository $destroyRepo,$id)
    {
        $expense = $this->expenseRepo->get($id)->first();
        $destroyRepo->deleteExpence($expense->id);
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
        $page = ['pageTitle' =>'Expenses'];

        if($type == 'details'){ }

        if($type == 'listing'){
            $page['previousUrl'] = url('accountings');
        }

        return $page;
    }
}
