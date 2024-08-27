<div class="form-group row">
    <label class="col-sm-2 control-label text-sm-right" for="input-language">
        <span class="d-flex justify-content-sm-end align-items-center h-100">
            <span class="d-inline-flex"></span>
            <span class="d-inline-flex text-break">{{__('global.language')}} : </span>
            <span class="d-inline-flex pl-1"></span>
        </span>
    </label>
    <div class="col-sm-10 text-left">
        <select class="form-control" onchange="location.href=$(this).find($('option[value='+$(this).val()+']')).attr('data-value');" name="language">
            @foreach(locales() as $key => $locale)
                <option data-value="{{request()->url().'?lang='.$key}}" value="{{$key}}" @if($key == request()->input('lang') || ! request()->has('lang') && $key == 'en') selected @endif>{{$locale['native']}}</option>
            @endforeach
        </select>
    </div>
</div>
@foreach($obj->trans_fields as $field)
    <div class="form-group row">
        <label class="col-sm-2 control-label text-sm-right" for="input-{{$field}}">
            <span class="d-flex justify-content-sm-end align-items-center h-100">
                <span class="d-inline-flex"></span>
                <span class="d-inline-flex text-break">{{__('admin.forms.'.$field)}} : </span>
                <span class="d-inline-flex pl-1"></span>
            </span>
        </label>
        <div class="col-sm-10 text-left">
            @if($obj->form_fields->get($field)->get('type') == 'textarea')
                <textarea class="form-control text-left"
                          id="input-{{$field}}" name="{{$field}}">{{ $obj->translate($field, request()->input('lang')?:'en') }}</textarea>
                @if($errors->has($field))
                    <ul class="parsley-errors-list filled">
                        @foreach($errors->get($field) as $error)
                            <li class="parsley-required">{{$error}}</li>
                        @endforeach

                    </ul>
                @endif
            @elseif($obj->form_fields->get($field)->get('type') == 'ckeditor')
                <input type="hidden" name="{{$field}}" id="input-{{$field}}" class="ckeditor-input">
                <div data-editor="DecoupledDocumentEditor" data-collaboration="false">
                    <div class="centered">
                        <div class="d-flex py-2">
                            @if($errors->has($field))
                                <ul class="parsley-errors-list filled">
                                    @foreach($errors->get($field) as $error)
                                        <li class="parsley-required">{{$error}}</li>
                                    @endforeach

                                </ul>
                            @endif
                            <a href="#" class="btn btn-info mx-1 ml-auto" title="Save"><i class="fa fa-expand"></i></a>
                        </div>
                        <div class="row">
                            <div class="document-editor__toolbar"></div>
                        </div>
                        <div class="row row-editor">
                            <div class="editor-container">
                                <div id="editor" class="editor" name="content">
                                    {!! $obj->translate($field, request()->input('lang')?:'en') !!}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @else
            <input type="text" class="form-control text-left"
                   id="input-{{$field}}" name="{{$field}}" value="{{$obj->translate($field, request()->input('lang')?:'en')}}"
                   placeholder="{{__('admin.placeholder_text', ['name'=>$field])}}">
                @endif
        </div>
    </div>
@endforeach
