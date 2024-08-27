<div class="table-responsive">
    <table id="datatable-buttons" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
        <thead>
        <tr>
            <th>
                <div class="checkbox text-center checkbox-primary">
                    <input id="checkbox--1" type="checkbox" value="-1" data-value="-1">
                    <label for="checkbox--1">
                    </label>
                </div>
            </th>
            <th>{{__('global.user')}}</th>
            <th>{{__('global.record_type')}}</th>
            <th>{{__('global.record')}}</th>
            <th>{{__('admin.actions')}}</th>
        </tr>
        </thead>
        <tbody>
        @foreach($records->items() as $record)
            <tr>
                <td>
                    @can('delete', $record)
                        <div class="checkbox text-center checkbox-primary">
                            <input id="checkbox-{{$record->id}}" type="checkbox" value="{{$record->id}}" data-value="{{$record->id}}">
                            <label for="checkbox-{{$record->id}}">
                            </label>
                        </div>
                    @endcan
                </td>
                <td><a href="{{route('admin.users.show',['user'=>$record->user->id])}}">{{$record->user->full_name}}</a></td>
                <td>{{$record->record_type->trans('name')}}</td>
                <td class="text-center">
                    <audio src="{{ asset($record->full_url) }}" controls>
                    </audio>
                </td>
                <td>
                    @can('view', $record)
                        <a href="{{route('admin.records.show', ['record'=>$record->id])}}"
                           class="btn btn-sm btn-outline-warning" title="{{__('admin.records_edit')}}">
                            <i class="mdi mdi-eye"></i>
                        </a>
                    @endcan
                    @can('translate', $record)
                        <a href="{{route('admin.records.translate', ['record'=>$record->id])}}" class="btn btn-sm btn-outline-warning"><i class="mdi mdi-translate"></i></a>
                    @endcan
                                        @can('update', $record)
                        <a href="{{route('admin.records.edit', ['record'=>$record->id])}}" class="btn btn-sm btn-info"><i class="mdi mdi-pencil"></i></a>
                    @endcan
                    @can('delete', $record)
                        <form method="POST" class="form d-inline-block delete-form" method="POST" action="{{route('admin.records.destroy', ['record'=>$record->id])}}">
                            @csrf
                            @method('DELETE')
                            <button href="#" class="btn btn-sm btn-danger"><i class="mdi mdi-delete"></i></button>
                        </form>
                    @endcan

                </td>
            </tr>
        @endforeach
        </tbody>

    </table>
</div>
<div class="justify-content-center">
    {!! $records->links() !!}
</div>
