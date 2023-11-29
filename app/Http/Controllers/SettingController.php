<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Setting;
use Illuminate\Support\MessageBag;
use App\Repositories\SettingRepository;
use Illuminate\Support\Facades\Validator;

class SettingController extends Controller
{
    protected $settingRepo;

    public function __construct(SettingRepository $settingRepo){
        $this->settingRepo = $settingRepo;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $page = $this->pageSetting();
        return view('pages.settings.wrapper', compact('page'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
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
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
    }

    public function updateSetting(Request $request)
    {
        $expensesCategories = $request->input('settings_expenses_category');
        if (is_array($expensesCategories)) {
            $expensesCategoryValue = json_encode($expensesCategories);
        } else {
            $expensesCategoryValue = '';
        }

        request()->merge(['settings_expenses_category'=>$expensesCategoryValue]);
        $result = $this->settingRepo->update();
        if ($result) {
            $ajax['notification'] = ['type' => 'success', 'value' => 'Request has been completed'];
        } else {
            $ajax['notification'] = ['type' => 'eror', 'value' => 'Request could not be completed'];
        }
        return response()->json($ajax);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function pageSetting($type = null, $data=null)
    {
        $page = ['pageTitle' =>'System Global Settings'];
        return $page;
    }
}
