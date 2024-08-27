<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\RecordTypes\CreateRecordType;
use App\Http\Requests\Admin\RecordTypes\DeleteRecordType;
use App\Http\Requests\Admin\RecordTypes\EditRecordType;
use App\Http\Requests\Admin\Roles\DeleteRole;
use App\Models\RecordType;
use App\Services\Admin\RecordTypesCrudService;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Redirector;

class RecordTypesController extends BaseAdminController
{
    private $recordTypesCrudService;

    /**
     * RolesController constructor.
     * Authorize requests using App\Policies\Admin\RolePolicy
     * @param RecordTypesCrudService $recordTypesCrudService
     */
    public function __construct(RecordTypesCrudService $recordTypesCrudService)
    {
        parent::__construct(RecordType::class);
        $this->authorizeResource(RecordType::class);
        $this->recordTypesCrudService = $recordTypesCrudService;
    }
    /**
     * Display a listing of the resource.
     *
     * @return Application|Factory|View|Response
     */
    public function index()
    {
        $record_types = $this->recordTypesCrudService->getAllRecordTypes();
        return view('admin.record_types.index', compact('record_types'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Application|Factory|View|Response
     */
    public function create()
    {
        $record_type = new RecordType;
        return view('admin.record_types.create-edit', compact('record_type'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param CreateRecordType $request
     * @return Application|RedirectResponse|Response|Redirector
     */
    public function store(CreateRecordType $request)
    {
        $this->recordTypesCrudService->createRecordType($request->all());
        return redirect(route('admin.record_types.index'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param RecordType $record_type
     * @return Application|Factory|View|Response
     */
    public function edit(RecordType $record_type)
    {
        return view('admin.record_types.create-edit', compact('record_type'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param EditRecordType $request
     * @param RecordType $record_type
     * @return Application|RedirectResponse|Response|Redirector
     */
    public function update(EditRecordType $request, RecordType $record_type)
    {
        $this->recordTypesCrudService->updateRecordType($record_type, $request->all());
        return redirect(route('admin.record_types.index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param DeleteRecordType $request
     * @param RecordType $record_type
     * @return Application|RedirectResponse|Response|Redirector
     * @throws \Exception
     */
    public function destroy(DeleteRecordType $request, RecordType $record_type)
    {
        $this->recordTypesCrudService->deleteRecordType($record_type, $request->all());
        return redirect(route('admin.record_types.index'));
    }

    /**
     *  Batch remove specified resources from storage
     *
     * @param DeleteRecordType $request
     * @return RedirectResponse|Redirector
     */
    public function batchDestroy(DeleteRecordType $request){
        $this->recordTypesCrudService->batchDeleteRecordTypes($request->all());
        return redirect(route('admin.record_types.index'));
    }
}
