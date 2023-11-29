<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Http\Request;
use App\Repositories\UserRepository;
use App\Repositories\DestroyRepository;
use App\DataTables\UsersDataTable;
use Validator;

class UserController extends Controller
{
    protected $userRepo;

    public function __construct(UserRepository $userRepo){
        $this->userRepo = $userRepo;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(UsersDataTable $dataTable)
    {
        $page = $this->pageSetting('listing');
        return $dataTable->render('pages.users.wrapper',compact('page'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $html = view('pages.users.modals.add-edit-inc')->render();
        $response['dom_html'][] = array(
            'selector' => '#commonModalBody',
            'action' => 'replace',
            'value' => $html);

        //show modal footer
        $response['dom_visibility'][] = array('selector' => '#commonModalFooter', 'action' => 'show');

        $response['postrun_functions'][] = [
            'value' => 'NXCreateUpdateUser'];

        return response()->json($response,200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'unique:users'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:6'],
            'role' => ['required']
        ]);
        validationToaster($validator);


        $user = $this->userRepo->create();

        $role = Role::where('slug',$request->role)->first();
        $role->users()->attach($user);

        // Create the profile record associated with the user
        $profile = $this->userRepo->create_profile($user);

        $users = $this->userRepo->get($user->id)->get();

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
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $users = $this->userRepo->get($id);
        $user = $users->first();

        $html = view('pages.users.modals.add-edit-inc', compact('user'))->render();
        $response['dom_html'][] = array(
            'selector' => '#commonModalBody',
            'action' => 'replace',
            'value' => $html);

        //show modal footer
        $response['dom_visibility'][] = array('selector' => '#commonModalFooter', 'action' => 'show');

        $response['postrun_functions'][] = [
            'value' => 'NXCreateUpdateUser'];

        return response()->json($response,200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {

        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'unique:users,username,'.$id],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,'.$id],
            'role' => ['required'],
            'password' => ['sometimes', 'nullable', 'min:6'],
        ]);
        validationToaster($validator);

        $user = $this->userRepo->update($id);

        // Create the profile record associated with the user
        $profile = $this->userRepo->update_profile($id);

        $role = Role::where('slug',$request->role)->first();
        $user->roles()->sync($role);

        $users = $this->userRepo->get($user->id)->get();

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
        $user = $this->userRepo->get($id)->first();

        $destroyRepo->deleteUser($user->id);

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
     * Filter user
     */
    public function filter()
    {
        $filter = [];
        if(request()->has('filter')){
            $filter = json_decode(request('filter'), true);
        }

        $html = view('pages.users.modals.filter', compact('filter'))->render();
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
     * Page content.
     */
    public function pageSetting($type = null)
    {
        return ['pageTitle' =>'User Management Module'];
    }
}
