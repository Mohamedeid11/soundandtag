<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Countries\DeleteCountry;
use App\Http\Requests\Admin\Records\CreateRecord;
use App\Http\Requests\Admin\Records\DeleteRecord;
use App\Http\Requests\Admin\Records\EditRecord;
use App\Http\Requests\Admin\RecordTypes\CreateRecordType;
use App\Models\Country;
use App\Models\Record;
use App\Models\RecordType;
use App\Services\Admin\RecordsCrudService;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Storage;

class RecordsController extends BaseAdminController
{
    private $recordsCrudService;

    /**
     * RecordsController constructor.
     * @param RecordsCrudService $recordsCrudService
     */
    public function __construct(RecordsCrudService $recordsCrudService)
    {
        parent::__construct(Record::class);
        $this->authorizeResource(Record::class);
        $this->recordsCrudService = $recordsCrudService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Application|Factory|View|Response
     */
    public function index()
    {
        $records = $this->recordsCrudService->getAllRecords();
        return view('admin.records.index', compact('records'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Application|Factory|View|Response
     */
    public function create()
    {
        $record = new Record;
        return view('admin.records.create-edit', compact('record'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param CreateRecord $request
     * @return Application|RedirectResponse|Response|Redirector
     */
    public function store(CreateRecord $request)
    {
        $this->recordsCrudService->createRecord($request->all());
        return redirect(route('admin.records.index'));
    }

    /**
     * Display the specified resource.
     *
     * @param Record $record
     * @return Application|Factory|View|Response
     */
    public function show(Record $record)
    {
        return view('admin.records.show', compact('record'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Record $record
     * @return Application|Factory|View|Response
     */
    public function edit(Record $record)
    {
        $record->file_path = $record->full_url;
        return view('admin.records.create-edit', compact('record'));

    }

    /**
     * Update the specified resource in storage.
     *
     * @param EditRecord $request
     * @param Record $record
     * @return Application|RedirectResponse|Response|Redirector
     */
    public function update(EditRecord $request, Record $record)
    {
        $this->recordsCrudService->updateRecord($record, $request->all());
        return redirect(route('admin.records.index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param DeleteRecord $request
     * @param Record $record
     * @return Application|RedirectResponse|Response|Redirector
     * @throws \Exception
     */
    public function destroy(DeleteRecord $request, Record $record)
    {
        $this->recordsCrudService->deleteRecord($record);
        return redirect(route('admin.records.index'));
    }

    /**
     * @param DeleteRecord $request
     * @return Application|RedirectResponse|Redirector
     */
    public function batchDestroy(DeleteRecord $request){
        $this->recordsCrudService->batchDeleteRecords($request->all());
        return redirect(route('admin.records.index'));
    }

}
