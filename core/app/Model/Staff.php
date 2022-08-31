<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Staff extends Model
{
    protected $appends = ['full_name'];

    public function getFullNameAttribute(){
        return $this->first_name.' '.$this->last_name;
    }
    
    public function picture_path(){
        if(!file_exists('assets/backend/image/staff/pic/'.$this->picture)){
            return asset('assets/backend/image/no-img.png');
        }
        return asset('assets/backend/image/staff/pic/'.$this->picture);
    }
    public function sex(){
        if($this->sex === 'M'){
            return 'Male';
        }
        if($this->sex === 'F'){
            return 'Female';
        }
        if($this->sex === 'O'){
            return 'Other';
        }
    }
}
