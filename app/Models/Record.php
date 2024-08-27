<?php

namespace App\Models;

use App\Events\UserRecordsChanged;
use App\Traits\HasAdminForm;
use App\Traits\Translatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Record extends Model
{
    use HasFactory, HasAdminForm, Translatable;
    protected $fillable = ['user_id', 'record_type_id',  'file_path', 'text_representation', 'meaning'];
    public $trans_fields = ['text_representation', 'meaning'];
    protected $appends = ['full_url', 'original_text', 'original_meaning'];
    public function getFullUrlAttribute(){
        if($this->file_path){
            return Str::of($this->file_path)->startsWith('storage') ? asset($this->file_path) : asset('storage/'.$this->file_path);
        }
        return null;
    }
    public function getOriginalTextAttribute(){
        return $this->text_representation;
    }
    public function getOriginalMeaningAttribute(){
        return $this->meaning;
    }
    public function form_fields(){
        $user_choices = [];
        foreach(User::all() as $user){
            $user_choices[$user->id] = $user->full_name . " - ".$user->username;
        }
        $record_type_choices = [];
        foreach(RecordType::all() as $record_type){
            $record_type_choices[$record_type->id] = $record_type->trans('name');
        }
        return collect([
           'user_id' => collect(['type'=>'select', 'required'=>3, 'choices'=> $user_choices]),
           'record_type_id'=>collect(['type'=>'select', 'required'=>3, 'choices'=> $record_type_choices]),
           'text_representation' => collect(['type'=>'string', 'required'=>3]),
           'meaning' => collect(['type'=>'string', 'required'=>0]),
           'file_path' => collect(['type'=>'record', 'required'=>2]),
        ]);
    }
    public function user(){
        return $this->belongsTo(User::class);
    }
    public function record_type(){
        return $this->belongsTo(RecordType::class);
    }

    protected $dispatchesEvents = [
        'saved' => UserRecordsChanged::class,
        'deleted' => UserRecordsChanged::class,
        'restored' => UserRecordsChanged::class,
    ];
}
