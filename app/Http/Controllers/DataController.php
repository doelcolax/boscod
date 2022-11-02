<?php

    namespace App\Http\Controllers;
    use App\Traits\ResponseTrait;
    use Illuminate\Http\Request;
    use App\Models\BankModel;
    use App\Models\RekAdminModel;
    use App\Models\TransaksiModel;
    use JWTAuth;
    use Validator;
    use DB;
    use Tymon\JWTAuth\Exceptions\JWTException;
    class DataController extends Controller
    {

        use ResponseTrait;


        public function getRekAdmin (Request $request) {

            $data =RekAdminModel::select('id_rekening','nama_bank','no_rekening','pemilik_rekening')
                                ->leftJoin('bank','bank.id_bank','rekening_admin.id_bank')
                                ->get();
            if(count($data) > 0) {
                foreach ($data as $lst) {
                    $res[] = array(
                        'nama_bank'=>$lst->nama_bank,
                        'no_rekening' =>$lst->no_rekening,
                        'pemilik_rekening'=>$lst->pemilik_rekening,
                    );
                }

                return $this->success($res);

            }else{

                return $this->error('Data tidak ditemukan',"200");

            }

        }
        public function getBank (Request $request) {

            $data =BankModel::select('nama_bank')->get();
            if(count($data) > 0) {
                foreach ($data as $lst) {
                    $res[] = $lst->nama_bank;

                }

                return $this->success($res);

            }else{

                return $this->error('Data tidak ditemukan',"200");

            }

        }

        public function createTransfer (Request $request) {
            $validator = Validator::make($request->all(), [
                'nilai_transfer'  => 'required|numeric|min:0|not_in:0',
                'bank_tujuan'     => 'required|exists:bank,nama_bank',
                'rekening_tujuan' => 'required',
                'atasnama_tujuan' => 'required',
                'bank_pengirim'   => 'required|exists:bank,nama_bank',
            ]);

            if ($validator->fails()) {
                $error = $validator->errors()->first();
                return response()->json(['error' => $error], 200);
            }
            $idTrans =$this->kodeTransaksi('TF');
            $kodeUnik = $this->uniqueCode();
            $idBankTujuan =BankModel::select('id_bank')->where('nama_bank', $request->bank_tujuan)->first();
            $idBankPengirim =BankModel::select('id_bank')->where('nama_bank', $request->bank_pengirim)->first();
            $rekAdmin =RekAdminModel::select('no_rekening')->where('id_bank', $idBankPengirim->id_bank)->first();


            //KAREANA DI SOAL TIDAK DIBERITAHU MASA BERLAKU TRANSAKSI  DIHTITUNG BAGAIMANA
            //DISINI SAYA MENAMBAHKAN WAKTU MAS BERLAKU SELAMA 2 HARI KEDEPAN DARI MULAI TGL TRANSAKSI DIBUAT

            $masa_berlaku =date('Y-m-d H:i:s', strtotime(date('Y-m-d H:i:s'). ' + 2 days'));
            $token = JWTAuth::getToken();
            $user = JWTAuth::toUser($token);


            DB::beginTransaction();
            try {
                TransaksiModel::create([
                    'id_user'           => $user->id,
                    'no_transaksi'	    => $idTrans,
                    'nilai_transfer'    => $request->nilai_transfer,
                    'kode_unik'			=> $kodeUnik,
                    'bank_pengirim'		=> $idBankPengirim->id_bank,
                    'bank_tujuan'       => $idBankTujuan->id_bank,
                    'rekening_tujuan'	=> $rekAdmin->no_rekening,
                    'atas_nama_tujuan'  => $request->atasnama_tujuan,
                    'masa_berlaku'      => $masa_berlaku
                ]);
            } catch (QueryException $e) {
                DB::rollback();
                throw $e;
            }
            DB::commit();
            return response()->json([
                    'id_transaksi' => $idTrans,
                    'nilai_transfer' => (int)$request->nilai_transfer,
                    'kode_unik' => (int)$kodeUnik,
                    'biaya_admin' => 0,
                    'total_transfer' => (int)$request->nilai_transfer+(int)$kodeUnik,
                    'bank_perantara' => $request->bank_pengirim,
                    'rekening_perantara' =>$rekAdmin->no_rekening,
                    'masa_berlaku'       => $masa_berlaku

                ]);


        }


        public function getListTransfer () {
            $token = JWTAuth::getToken();
            $user = JWTAuth::toUser($token);
            $data = TransaksiModel::select('no_transaksi','nilai_transfer', 'kode_unik', 'a.nama_bank as bank_pengirim', 'b.nama_bank as bank_tujuan','no_rekening','masa_berlaku','status')
                    -> where('id_user', '=', $user->id)
                     ->leftJoin('bank as a','a.id_bank','transaksi_transfer.bank_pengirim')
                     ->leftJoin('bank as b','b.id_bank','transaksi_transfer.bank_tujuan')
                     ->leftJoin('rekening_admin','rekening_admin.id_bank','transaksi_transfer.bank_pengirim')
                     ->get ();
            if(count($data) > 0) {
                foreach ($data as $lst) {
                    $res[] = array(
                        'id_transaksi' => $lst->no_transaksi,
                        'nilai_transfer' => (int)$lst->nilai_transfer,
                        'kode_unik' => (int)$lst->kode_unik,
                        'biaya_admin' => 0,
                        'total_transfer' => (int)$lst->nilai_transfer+(int)$lst->kode_unik,
                        'bank_tujuan'    => $lst->bank_tujuan,
                        'bank_perantara' => $lst->bank_pengirim,
                        'rekening_perantara' =>$lst->no_rekening,
                        'masa_berlaku'       => $lst->masa_berlaku,
                        'status'       => $lst->status
                    );
                }

                return $this->success($res);

            }else{

                return $this->error('Data tidak ditemukan',"200");

            }
        }

        protected function kodeTransaksi ($prefix) {
            //Generate kodeTransaki berdasarkan prefix (TF) dan tanggalnya
            //setiap tgl berubah KodeTransaksi akan Mereset 5 digit dibelakangnya
            $count = TransaksiModel::where(DB::raw("DATE_FORMAT(created_at,'%Y-%m-%d')"),date('Y-m-d'))
            ->count();
            $count += 1;
            $code =$prefix.date('Ymd'). sprintf("%05s", $count);

            return $code;

        }
        protected function uniqueCode(){

            $unique = false;
            $tested = [];
            do{

                 $code_unique = rand(100,1000);// Generate random number 3 DIGIT
                //  $code_unique =$bankId.$random; //gabungkan BankPengirim (bankID) dengan random number sebagai kodeuniq
                //  //kodeuniq dengan cara diatas bisa langsung mendteksi untuk tujuan bank apa karean awal angka selalu diisi dengan ID bank Tujuan

                 //cek apakah code uniqe sudah ditest
                 //jika sudah jangan query ke database
                 if( in_array($code_unique, $tested) ){
                      continue;
                 }

                 // Check/Test codeUnique di database agar data kodeunik tidak sama
                 // Disini saya menggunakan pembandingan kodeunik berdsarkan kodenique dan tanggal buat transaksi transfer
                 // kenapa hal ini dilakukan, Agar kodeunik (3digit) bisa menghandle banyak transaksi karena tiap harinya direset
                 // Jika sudah melewati hari ini kodeunik yg sudah digunakan dilain hari bisa digunakan

                 //Bisa dikembangkan lagi untuk menghandle bukan hanya dari tanggal pembuatan transaksi
                 //namun digabungkan antara tanggal dan bank pengirim agar lebih banyak transaksi yag bisa dihandle
                 //Atau menggunakan pembandingnya dari UserID dan tanggalpembuatan transaksi..
                 //ini smkin banyak transaksi yag bisa dihandle

                 $count = TransaksiModel::where('kode_unik', '=', $code_unique)
                                        ->where(DB::raw("DATE_FORMAT(created_at,'%Y-%m-%d')"),date('Y-m-d'))
                                        ->count();

                 //simpan kodeUniq in variabel tested (array)
                 //ini dilakukan agar tau mana yg sudah dicek kodeuniq nya mana yg belum agar tidak selalu manggil query
                 $tested[] = $code_unique;

                 //jika memang unique
                 // Set unique to true untuk menghentikan Loop
                 if( $count == 0){

                      $unique = true;
                 }

            }
            while(!$unique);


            return $code_unique;
      }

        public function getUserInfo () {
            $token = JWTAuth::getToken();
            $user = JWTAuth::toUser($token);

            return $this->success($user);

        }

    }
