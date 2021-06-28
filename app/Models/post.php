<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class post extends Model
{
    use HasFactory;

    #Estää sql hyökkäykset lomakkeesta
    protected $fillable = [
        'tehtava',
        'paivitetty'
    ];           
    
    #Tallennetaan aika tietokantaan, joten true
    public $timestamps = true;  
    

}
