<?php

namespace App\Models;

//Kütüphanelerimizi ekliyoruz.
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

//Model ismi
class Log extends Model
{
    //Sahte loglar için
    use HasFactory;

    //tablo ismi
    protected $table = 'logs';

    // Log modelinin hangi alanlara veri girebileceğimizi belirtiyoruz.
    protected $fillable = [
        'ip_address',  // İsteği yapan kişinin IP adresi
        'url',         // İsteğin gönderildiği tam URL adresi
        'method',      // İsteğin http metodu
        'status_code', // İsteğin sonucunda dönen http durum kodu
    ];
}