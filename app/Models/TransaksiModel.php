<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class TransaksiModel extends Model
{
    protected $table 		= 'transaksi_transfer';
    protected $primaryKey 	= 'id_transaksi';
	protected $guarded = [];

}

