<?php

namespace App\Http\Controllers;

use App\Models\CreditNote;
use Illuminate\Http\Request;
use App\Repositories\ClientRepository;
use App\Repositories\CreditNoteRepository;
use App\Repositories\DestroyRepository;
use App\DataTables\CreditNotesDataTable;
use App\Events\GenerateCreditNoteInvoiceEvent;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;
use App\Models\Attachment;
use Validator, Datatables;
use PDF, PDFMerger;
use App\Models\Client;

class CreditNoteController extends Controller
{
    protected $clientRepo;

    protected $creditNoteRepo;

    public function __construct(
        ClientRepository $clientRepo,
        CreditNoteRepository $creditNoteRepo){
        $this->clientRepo = $clientRepo;
        $this->creditNoteRepo = $creditNoteRepo;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(CreditNotesDataTable $dataTable)
    {
        config([
            'visibility.credit_note_invoice' => true,
            'visibility.credit_note_delete' => true,
        ]);

        $page = $this->pageSetting('listing');
        return $dataTable->render('pages.credit-notes.wrapper',compact('page'));
    }

     /**
     * Display a listing of the resource.
     */
    public function list()
    {
        $notes = $this->creditNoteRepo->get();
        config([
            'visibility.credit_note_view'    => true,
            'visibility.credit_note_invoice' => true,
            'visibility.credit_note_delete'  => true,
        ]);

       return Datatables::eloquent($notes)
                ->addColumn('action', function($row) {
                    return view('pages.credit-notes.components.action',['note'=>$row]);
                })
                ->editColumn('amount', function ($data) {
                    return $data->status == 'partial' ? '$'.$data->partial_amount : '$'.$data->amount;
                })
                ->editColumn('status', function ($data) {
                    if($data->status == 'redeemed'){
                        $html = '<button type="button" title="Status Change" class="data-toggle-action-tooltip btn btn-default confirm-action-success"'
                                .'data-confirm-title="Change Status" data-confirm-text="Are you sure you want to change status."'
                                .'data-ajax-type="POST" data-url="'. url('credit-notes/status-change' , $data->id).'">'
                                .'<span class="text-success">Redeemed</span>'
                            .'</button>';
                        return $html;
                    }elseif($data->status == 'partial'){
                        $html = '<button type="button" title="Status Change" class="data-toggle-action-tooltip btn btn-default confirm-action-success"'
                                .'data-confirm-title="Change Status" data-confirm-text="Are you sure you want to change status."'
                                .'data-ajax-type="POST" data-url="'. url('credit-notes/status-change' , $data->id).'">'
                                .'<span class="text-warning">Partial</span>'
                            .'</button>';
                        return $html;
                    }else{
                        $html = '<button type="button" title="Status Change" class="data-toggle-action-tooltip btn btn-default confirm-action-success"'
                        .'data-confirm-title="Change Status" data-confirm-text="Are you sure you want to change status."'
                        .'data-ajax-type="POST" data-url="'. url('credit-notes/status-change' , $data->id).'">'
                        .'<span class="text-danger">Unredeemed</span>'
                    .'</button>';
                return $html;
                    }
                })
                ->editColumn('created_at', function ($data) {
                    return runtimeDate($data->created_at);
                })
                ->filterColumn('created_at', function ($query, $date) {
                })
                ->rawColumns(['action','status'])
                ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
         if(request('client_id')){
           $client = $this->clientRepo->get(request('client_id'))->first();
         }else{
            $client = $this->clientRepo->get()->get();
         }
        //   dd($client instanceof \App\Models\Client);
        $html = view('pages.credit-notes.modals.add-edit-inc', compact('client'))->render();
        $response['dom_html'][] = array(
            'selector' => '#commonModalBody',
            'action' => 'replace',
            'value' => $html);

        //show modal footer
        $response['dom_visibility'][] = array('selector' => '#commonModalFooter', 'action' => 'show');

        $response['postrun_functions'][] = [
            'value' => 'NXCreateUpdateNote'];

        return response()->json($response,200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'client' => ['required'],
            'item_code' => ['required', 'array'],
            'item_code.*' => [
                'required'
            ],
        ], [
            'item_code.required' => 'At least one item code should be entered.',
            'item_code.*.required' => 'Each item code should not be empty.',
        ]);
        validationToaster($validator);

        $client = $this->clientRepo->get(request('client'))->first();

        $note_id = generateUniqueID(new CreditNote, 4);

        $amount = 0; $notes_items = [];
        for($i=0; $i<count(request('item_code')); $i++){
            $notes_items[$i] = [
                'item_code' => request('item_code')[$i],
                'quantity' => request('quantity')[$i],
                'unit_price' => request('unit_price')[$i],
                'total_price' => request('total_price')[$i]
            ];
            $amount += request('total_price')[$i];
        }

        //if GST is applied
        if(request('apply_gst')){
            $gstPercentage = settings('settings_gst_percentage');
            $amount += ($amount * ($gstPercentage / 100));
        }
        request()->merge([
            'note_id'   => $note_id,
            'client_id' => $client->id,
            'apply_gst' => request('apply_gst') ? 1 : 0,
            'amount'    => $amount,
            'gst_percent' => request('apply_gst') ? $gstPercentage : 0,
                ]);

        $creditNote = $this->creditNoteRepo->create();
        $items = $this->creditNoteRepo->create_note_items($creditNote->id,$notes_items);

        // Update client credit note balance
        $updatedAmount = $client->credit_notes + $creditNote->amount;
        request()->merge(['credit_notes'=>$updatedAmount]);
        $this->clientRepo->update($client->id);

        $client = $this->clientRepo->get($client->id)->first();

        $balance  = $client->credit_notes;

        $ajax['postrun_functions'][] = [
            'value' => 'NXDatatableReload'];

        $ajax['dom_html'][] = array(
            'selector' => '#credit-note-amount',
            'action' => 'replace',
            'value' => $balance);

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
    public function show(CreditNote $creditNote)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(CreditNote $creditNote)
    {
        $client = $this->clientRepo->get($creditNote->client_id)->first();

        $html = view('pages.credit-notes.modals.add-edit-inc', compact('client','creditNote'))->render();
        $response['dom_html'][] = array(
            'selector' => '#commonModalBody',
            'action' => 'replace',
            'value' => $html);

        //show modal footer
        $response['dom_visibility'][] = array('selector' => '#commonModalFooter', 'action' => 'show');

        $response['postrun_functions'][] = [
            'value' => 'NXCreateUpdateNote'];

        $response['postrun_functions'][] = [
            'value' => 'NXInventroyMultiItem'];

        return response()->json($response,200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, CreditNote $creditNote)
    {

        $creditNote = $this->creditNoteRepo->get($creditNote->id)->first();

        $validator = Validator::make($request->all(), [
            'client' => ['required']
        ]);
        validationToaster($validator);

        $amount = 0;
        for($i=0; $i<count(request('item_code')); $i++){
            $item = [
                'item_code' => request('item_code')[$i],
                'quantity' => request('quantity')[$i],
                'unit_price' => request('unit_price')[$i],
                'total_price' => request('total_price')[$i]
            ];
            $amount += request('total_price')[$i];
            if(request('item_id')[$i]){
                $this->creditNoteRepo->update_note_items(request('item_id')[$i],$item);
            }else{
                $this->creditNoteRepo->create_note_items($creditNote->id,[$item]);
            }
        }

        if(request('apply_gst')){
            $gstPercentage = settings('settings_gst_percentage');
            $amount += ($amount * ($gstPercentage / 100));
        }

        request()->merge([
            'apply_gst' => request('apply_gst') ? 1 : 0,
            'gst_percent' => request('apply_gst') ? settings('settings_gst_percentage') : 0,
            'amount' => $amount]);

        if($creditNote->status == 'partial'){
            $old_partial_amount = $creditNote->partial_amount;
            $appied_amount = $creditNote->amount -  $creditNote->partial_amount;
            $new_partial_amount = $amount - $appied_amount;
            request()->merge(['partial_amount' => $new_partial_amount]);
        }
        
        $this->creditNoteRepo->update($creditNote->id);


        $client = $this->clientRepo->get($creditNote->client_id)->first();

        $balance = $client->credit_notes;

        if($creditNote->status == 'unredeemed'){
            $balance = $client->credit_notes - $creditNote->amount;
            $balance = $balance + $amount;
            request()->replace([]);
            request()->merge(['credit_notes' => $balance]);
            $this->clientRepo->update($client->id);
        }elseif($creditNote->status == 'partial'){
            $balance = $client->credit_notes - $old_partial_amount;
            $balance = $balance + $new_partial_amount;
            request()->replace([]);
            request()->merge(['credit_notes' => $balance]);
            $this->clientRepo->update($client->id);
        }

        $ajax['postrun_functions'][] = [
            'value' => 'NXDatatableReload'];

        $ajax['dom_html'][] = array(
            'selector' => '#credit-note-amount',
            'action' => 'replace',
            'value' => $balance);

        //hide modal
        $ajax['dom_visibility'][] = array('selector' => '#commonModal', 'action' => 'close-modal');
        //notice
        $ajax['notification'] = array('type' => 'success', 'value' => 'Request has been completed');

        //ajax response & view
        return response()->json($ajax);
    }

    /**
     * Update the specified resource in storage.
     */
    public function updateRemark(Request $request, $id)
    {
        $creditNote = $this->creditNoteRepo->update($id);
        //notice
        $ajax['notification'] = array('type' => 'success', 'value' => 'Request has been completed');
        //ajax response & view
        return response()->json($ajax);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CreditNote $creditNote)
    {
        $creditNote = $this->creditNoteRepo->get($creditNote->id)->first();
        
        $client = $this->clientRepo->get($creditNote->client_id)->first();
        $balance = $client->credit_notes;
       
        $this->creditNoteRepo->delete($creditNote->id);

        $client = $this->clientRepo->get($creditNote->client_id)->first();
        $balance = $client->notes->where('stauts','unredeemed')->sum('amount');

        // Update client credit note balance
        request()->merge(['credit_notes' => $balance]);
        $this->clientRepo->update($creditNote->client_id);

        $ajax['postrun_functions'][] = [
            'value' => 'NXDatatableReload'];

        $ajax['dom_html'][] = array(
            'selector' => '#credit-note-amount',
            'action' => 'replace',
            'value' => $balance);

        //notice
        $ajax['notification'] = array('type' => 'success', 'value' => 'Request has been completed');

        //ajax response & view
        return response()->json($ajax);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function delete_item($id)
    {
        $item = $this->creditNoteRepo->get_credit_note($id)->first();

        $client_id = $item->credit_note->client_id;

        $client = $this->clientRepo->get($client_id)->first();

        $balance = $client->credit_notes - $item->total_price;

        $this->creditNoteRepo->delete_item($id);

        // Update client credit note balance
        request()->merge(['credit_notes' => $balance]);
        $this->clientRepo->update($client_id);

        $ajax['postrun_functions'][] = [
            'value' => 'NXDatatableReload'];

        $ajax['postrun_functions'][] = [
            'value' => 'NXInventroyMultiItem'];

        $ajax['dom_html'][] = array(
            'selector' => '#credit-note-amount',
            'action' => 'replace',
            'value' => $balance);

        $ajax['dom_visibility'][] = array(
            'selector' => '#note-item-'.$id,
            'action' => 'slideup-remove');

        //notice
        $ajax['notification'] = array('type' => 'success', 'value' => 'Request has been completed');

        //ajax response & view
        return response()->json($ajax);
    }

    /**
     * Add new item in module
     */
    public function addItem()
    {
        $html = view('pages.credit-notes.utils.note-item')->render();
        $response['dom_html'][] = array(
            'selector' => '#inventory-items',
            'action' => 'append',
            'value' => $html);

        $response['postrun_functions'][] = [
            'value' => 'NXInventroyMultiItem'];

        return response()->json($response,200);
    }

    /**
     * Filter user
     */
    public function filter()
    {
        $filter = [];
        if(request()->has('filter')){
            $filter = json_decode(request('filter'), true);
        }

        $html = view('pages.client.modals.filter-cn', compact('filter'))->render();
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
     * Change status of credit  notes
     */
    public function creditNoteStatusChange(Request $request)
    {
        $creditNotes = $this->creditNoteRepo->get($request->id)->first();

        $data = $creditNotes->status == 'redeemed' ? 'unredeemed' : 'redeemed';
        request()->merge(['status' => $data]);

        $this->creditNoteRepo->update($creditNotes->id);
        $client = $this->clientRepo->get($creditNotes->client_id)->first();
        if($data == "redeemed")
        {
            if($creditNotes->status == 'partial'){
                $amount = $client->credit_notes - $creditNotes->partial_amount;
            }else{
                $amount = $client->credit_notes - $creditNotes->amount;
            }
        }else{
            $amount = $client->credit_notes + $creditNotes->amount;
        }
        // Update client credit note balance
        request()->merge(['credit_notes' => $amount]);
        $this->clientRepo->update($creditNotes->client_id);

        $ajax['dom_html'][] = array(
            'selector' => '#credit-note-amount',
            'action' => 'replace',
            'value' => $amount);

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
     * download invoice
     */
    public function downloadCreditNotesInvoice($id)
    {
        $pdf_data = config('pdf-data');
        event(new GenerateCreditNoteInvoiceEvent($id));
        $creditNotes = $this->creditNoteRepo->get($id)->first();
        $inv = $creditNotes->attachment_invoice->attachment_directory.'/'.$creditNotes->attachment_invoice->attachment_filename;

        return response()->download(public_path('storage/files/attachments/' .$creditNotes->attachment_invoice->attachment_directory.'/'.$creditNotes->attachment_invoice->attachment_filename));
    }


    /**
     * Page content.
     */
    public function pageSetting($type = null, $data=null)
    {
        $page = ['pageTitle' =>'Credit Notes'];

        if($type == 'details'){
        }

        return $page;
    }
}
