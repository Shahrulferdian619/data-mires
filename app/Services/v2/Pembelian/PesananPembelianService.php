<?php

namespace App\Services\v2\Pembelian;

use App\Jobs\v2\Telegram\SendNotifikasiJob;
use App\Models\v2\Pembelian\PermintaanPembelianRinci;
use App\Models\v2\Pembelian\PesananPembelian;
use App\Services\LogAktifitasService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PesananPembelianService
{
    protected $logAktifitasService;
    protected $permintaanPembelianService;

    public function __construct(
        LogAktifitasService $logAktifitasService,
        PermintaanPembelianService $permintaanPembelianService
    ) {
        $this->logAktifitasService = $logAktifitasService;
        $this->permintaanPembelianService = $permintaanPembelianService;
    }

    public function createData($request)
    {
        $po = $this->insertHeader($request);

        if ($po instanceof PesananPembelian) {
            $result = $this->insertRincian($request, $po);
            if ($result === true) {
                if ($request->has('berkas')) {
                    foreach ($request->file('berkas') as $berkas) {
                        $filename = $this->uploadFile($berkas);
                        if (!is_string($filename)) {
                            $this->deleteFile($po->rincianBerkas);
                            $po->delete();
                            return $filename;
                        }
                        $result = $this->insertFile($filename, $po);
                        if ($result !== true) {
                            $this->deleteFile($po->rincianBerkas);
                            $po->delete();
                            return $result;
                        }
                    }
                }
            } elseif ($result !== true) {
                $po->delete();
                return $result;
            }
            $this->logAktifitasService->createLog('Membuat PO nomer : ' . $po->nomer_pesanan_pembelian);
            $this->permintaanPembelianService->cekRincianKuantitasDanKuantitasDiproses($po->permintaan_pembelian_id);

            //kirim notif ke telegram
            $text = "<strong>PENGAJUAN PO BARU</strong>\n\n"
                . "Nomer : <strong>" . $po->nomer_pesanan_pembelian . "</strong>\n"
                . "Tanggal : <strong>" . date('d-m-Y', strtotime($po->tanggal)) . "</strong>\n"
                . "Dibuat oleh : <strong>" . Auth::user()->name . "</strong>\n\n"
                . "Mohon dapat dicek untuk ditindaklanjuti.\n"
                . "Terima kasih\n\n"
                . "Link : <strong>" . route('pembelian.pesanan-pembelian.show', $po->id) . "</strong>";

            SendNotifikasiJob::dispatchSync($text, config('telegram_config.v2_telegram_purchase_id'));
        }
        return $po;
    }

    public function updateData($request, $id)
    {
        $po = $this->updateHeader($request, $id);

        if ($po === true) {
            $updatePO = PesananPembelian::find($id);

            $this->decreaseKuantitasDiprosesPermintaanPembelian($updatePO);
            $updatePO->rincianItem()->delete();

            $this->increaseKuantitasDiprosesPermintaanPembelian($request, $updatePO);

            $this->logAktifitasService->createLog('Mengubah PO nomer : ' . $updatePO->nomer_pesanan_pembelian);
            $this->permintaanPembelianService->cekRincianKuantitasDanKuantitasDiproses($updatePO->permintaan_pembelian_id);

            //jika ada berkas yang diupload
            if ($request->has('berkas')) {
                foreach ($request->file('berkas') as $berkas) {
                    $this->deleteFile($updatePO->rincianBerkas); // delete berkas
                    $updatePO->rincianBerkas()->delete(); // delete data

                    $filename = $this->uploadFile($berkas);
                    if (!is_string($filename)) {
                        $this->deleteFile($updatePO->rincianBerkas);
                        $updatePO->rincianBerkas()->delete();
                        return $filename;
                    }
                    $result = $this->insertFile($filename, $updatePO);
                    if ($result !== true) {
                        $this->deleteFile($updatePO->rincianBerkas);
                        $updatePO->rincianBerkas()->delete();
                        return $result;
                    }
                }
            }

            //kirim notif ke telegram
            $text = "<strong>PENGAJUAN PO DIPERBARUI</strong>\n\n"
                . "Nomer : <strong>" . $updatePO->nomer_pesanan_pembelian . "</strong>\n"
                . "Tanggal : <strong>" . date('d-m-Y', strtotime($updatePO->tanggal)) . "</strong>\n\n"
                . "Diperbarui oleh : <strong>" . Auth::user()->name . "</strong>\n"
                . "Mohon dapat dicek ulang untuk ditindaklanjuti.\n"
                . "Terima kasih\n\n"
                . "Link : <strong>" . route('pembelian.pesanan-pembelian.show', $updatePO->id) . "</strong>";

            SendNotifikasiJob::dispatchSync($text, config('telegram_config.v2_telegram_purchase_id'));
        }

        return $po;
    }

    public function deleteData($pesananPembelian)
    {
        if ($pesananPembelian->approve_direktur == 1 || $pesananPembelian->approve_komisaris == 1) {
            return false;
        } else {
            if ($this->deleteFile($pesananPembelian->rincianBerkas) === true) { //delete berkas
                $this->decreaseKuantitasDiprosesPermintaanPembelian($pesananPembelian);
                $this->permintaanPembelianService->cekRincianKuantitasDanKuantitasDiproses($pesananPembelian->permintaan_pembelian_id);
                $this->logAktifitasService->createLog('Menghapus PO nomer : ' . $pesananPembelian->nomer_pesanan_pembelian);
                $pesananPembelian->delete(); //hapus data pesanan pembelian

                //kirim notif ke telegram
                $text = "<strong>PENGAJUAN PO DIHAPUS</strong>\n\n"
                    . "Nomer : <strong>" . $pesananPembelian->nomer_pesanan_pembelian . "</strong>\n"
                    . "Tanggal : <strong>" . date('d-m-Y', strtotime($pesananPembelian->tanggal)) . "</strong>\n"
                    . "Dihapus oleh : <strong>" . Auth::user()->name . "</strong>\n\n"
                    . "Terima kasih\n\n";

                SendNotifikasiJob::dispatchSync($text, config('telegram_config.v2_telegram_purchase_id'));
            }

            return true;
        }
    }

    public function revisiData($request, $id)
    {
        $oldPO = PesananPembelian::findOrFail($id);
        $this->decreaseKuantitasDiprosesPermintaanPembelian($oldPO);
        $oldPO->update([
            'nomer_pesanan_pembelian' => $oldPO->nomer_pesanan_pembelian . '_cancel' . date('His'),
        ]);
        $oldPO->ditutup(10);

        $newPO = $this->insertHeader($request);
        if ($newPO instanceof PesananPembelian) {
            $result = $this->insertRincian($request, $newPO);
            if ($result === true) {
                if ($request->has('berkas')) {
                    foreach ($request->file('berkas') as $berkas) {
                        $filename = $this->uploadFile($berkas);
                        if (!is_string($filename)) {
                            $this->deleteFile($newPO->rincianBerkas);
                            $newPO->delete();
                            return $filename;
                        }
                        $result = $this->insertFile($filename, $newPO);
                        if ($result !== true) {
                            $this->deleteFile($newPO->rincianBerkas);
                            $newPO->delete();
                            return $result;
                        }
                    }
                }
            } elseif ($result !== true) {
                $newPO->delete();
                return $result;
            }
            $this->logAktifitasService->createLog('Membuat pesanan pembelian nomer : ' . $newPO->nomer_pesanan_pembelian);
            $this->permintaanPembelianService->cekRincianKuantitasDanKuantitasDiproses($newPO->permintaan_pembelian_id);

            //kirim notif ke telegram
            $text = "<strong>REVISI PENGAJUAN PO</strong>\n\n"
                . "Nomer : <strong>" . $newPO->nomer_pesanan_pembelian . "</strong>\n"
                . "Tanggal : <strong>" . date('d-m-Y', strtotime($newPO->tanggal)) . "</strong>\n"
                . "Direvisi oleh : <strong>" . Auth::user()->name . "</strong>\n\n"
                . "Mohon dapat dicek untuk ditindaklanjuti.\n"
                . "Terima kasih\n\n"
                . "Link : <strong>" . route('pembelian.pesanan-pembelian.show', $newPO->id) . "</strong>";

            SendNotifikasiJob::dispatchSync($text, config('telegram_config.v2_telegram_purchase_id'));
        }

        return $newPO;
    }

    public function decreaseKuantitasDiprosesPermintaanPembelian($po)
    {
        foreach ($po->rincianItem as $rincian) {
            $prRinci = PermintaanPembelianRinci::where('permintaan_pembelian_id', $po->permintaan_pembelian_id)
                ->where('item_id', $rincian->item_id)->first();
            $prRinci->kuantitas_diproses -= $rincian->kuantitas;
            $prRinci->save();
        }
    }

    public function increaseKuantitasDiprosesPermintaanPembelian($request, $po)
    {
        foreach ($request->input('rincian') as $item) {
            $po->rincianItem()->create([
                'item_id' => $item['item_id'],
                'deskripsi_item' => $item['deskripsi_item'],
                'kuantitas' => $item['kuantitas'],
                'harga' => $item['harga'],
                'diskon_persen' => $item['diskon_persen'],
                'diskon_nominal' => $item['diskon_nominal'],
                'subtotal' => $item['subtotal'],
                'catatan' => $item['catatan'],
            ]);

            $prRinci = PermintaanPembelianRinci::where('permintaan_pembelian_id', $po->permintaan_pembelian_id)
                ->where('item_id', $item['item_id'])->first();
            $prRinci->kuantitas_diproses += $item['kuantitas'];
            $prRinci->save();
        }
    }

    /**
     * Insert data pesanan pembelian
     */
    public function insertHeader($request)
    {
        try {
            return PesananPembelian::create($request->except('rincian', 'berkas'));
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    /**
     * Update data pesanan pembelian
     */
    public function updateHeader($request, $id)
    {
        try {
            return PesananPembelian::findOrFail($id)->update($request->except('rincian', 'berkas'));
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    /**
     * Insert data rincian pesanan
     */
    public function insertRincian($request, $po)
    {
        try {
            $po->rincianItem()->createMany($request->input('rincian'));
            $this->permintaanPembelianService->tambahKuantitasDiproses($po);

            return true;
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function uploadFile($berkas)
    {
        $filename = str_replace(['/', ' ', '-'], '_', $berkas['nama_berkas']->getClientOriginalName());
        try {
            $berkas['nama_berkas']->storeAs('berkas_pesanan_pembelian', $filename);

            return $filename;
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function deleteFile($rincianBerkas)
    {
        foreach ($rincianBerkas as $berkas) {
            Storage::delete('berkas_pesanan_pembelian/' . $berkas->nama_berkas);
        }

        return true;
    }

    public function insertFile($filename, $po)
    {
        try {
            $po->rincianBerkas()->create([
                'nama_berkas' => $filename
            ]);

            return true;
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function downloadFile($nama_berkas)
    {
        return Storage::download('berkas_pesanan_pembelian/' . $nama_berkas);
    }

    /**
     * Proses approve pengajuan
     */
    public function approvePengajuan($request, $id)
    {
        // $level = Auth::user()->position;
        // $pengajuanPO = PesananPembelian::findOrFail($id);

        // if ($level === 'direktur') {
        //     try {
        //         if ($pengajuanPO->grandtotal < 5000000) {
        //             $pengajuanPO->update([
        //                 'catatan_direktur' => $request->input('catatan'),
        //                 'approve_direktur' => 1,
        //                 'approve_komisaris' => 1,
        //                 'direktur_id' => Auth::user()->id,
        //                 'komisaris_id' => Auth::user()->id,
        //                 'status_proses' => 0,
        //             ]);
        //         } else {
        //             $pengajuanPO->update([
        //                 'catatan_direktur' => $request->input('catatan'),
        //                 'approve_direktur' => 1,
        //                 'direktur_id' => Auth::user()->id,
        //                 'status_proses' => 0,
        //             ]);
        //         }

        //         $this->logAktifitasService->createLog('Menyetujui PO nomer : ' . $pengajuanPO->nomer_pesanan_pembelian);

        //         //kirim notif ke telegram
        //         $text = "<strong>PENGAJUAN PO</strong>\n\n"
        //             . "Nomer : <strong>" . $pengajuanPO->nomer_pesanan_pembelian . "</strong>\n"
        //             . "Tanggal : <strong>" . date('d-m-Y', strtotime($pengajuanPO->tanggal)) . "</strong>\n\n"
        //             . "Sudah <strong>DISETUJUI</strong> oleh " . Auth::user()->name . ".\n\n";

        //         SendNotifikasiJob::dispatchSync($text, config('telegram_config.v2_telegram_purchase_id'));

        //         return true;
        //     } catch (\Exception $e) {
        //         return $e->getMessage();
        //     }
        // } else if ($level === 'komisaris') {
        //     try {
        //         $pengajuanPO->update([
        //             'catatan_komisaris' => $request->input('catatan'),
        //             'approve_komisaris' => 1,
        //             'komisaris_id' => Auth::user()->id,
        //             'status_proses' => 0,
        //         ]);

        //         $this->logAktifitasService->createLog('Menyetujui PO nomer : ' . $pengajuanPO->nomer_pesanan_pembelian);

        //         //kirim notif ke telegram
        //         $text = "<strong>PENGAJUAN PO</strong>\n\n"
        //             . "Nomer : <strong>" . $pengajuanPO->nomer_pesanan_pembelian . "</strong>\n"
        //             . "Tanggal : <strong>" . date('d-m-Y', strtotime($pengajuanPO->tanggal)) . "</strong>\n\n"
        //             . "Sudah <strong>DISETUJUI</strong> oleh " . Auth::user()->name . ".\n\n";

        //         SendNotifikasiJob::dispatchSync($text, config('telegram_config.v2_telegram_purchase_id'));

        //         return true;
        //     } catch (\Exception $e) {
        //         return $e->getMessage();
        //     }
        // }

        $level = Auth::user()->position;
        $pengajuanPO = PesananPembelian::findOrFail($id);

        try {
            if ($level === 'direktur') {
                $pengajuanPO->catatan_direktur = $request->input('catatan');
                $pengajuanPO->approve_direktur = 1;
                $pengajuanPO->direktur_id = Auth::user()->id;

                if ($pengajuanPO->grandtotal < 5000000) {
                    $pengajuanPO->approve_komisaris = 1;
                    $pengajuanPO->komisaris_id = Auth::user()->id;
                }

                $this->logAktifitasService->createLog('Menyetujui PO nomer : ' . $pengajuanPO->nomer_pesanan_pembelian);
            } else if ($level === 'komisaris') {
                $pengajuanPO->catatan_komisaris = $request->input('catatan');
                $pengajuanPO->approve_komisaris = 1;
                $pengajuanPO->komisaris_id = Auth::user()->id;

                $this->logAktifitasService->createLog('Menyetujui PO nomer : ' . $pengajuanPO->nomer_pesanan_pembelian);
            }

            $pengajuanPO->status_proses = 0;
            $pengajuanPO->save();

            // Kirim notifikasi ke Telegram
            $text = "<strong>PENGAJUAN PO</strong>\n\n"
                . "Nomer : <strong>" . $pengajuanPO->nomer_pesanan_pembelian . "</strong>\n"
                . "Tanggal : <strong>" . date('d-m-Y', strtotime($pengajuanPO->tanggal)) . "</strong>\n\n"
                . "Sudah <strong>DISETUJUI</strong> oleh " . Auth::user()->name . ".\n\n";

            SendNotifikasiJob::dispatchSync($text, config('telegram_config.v2_telegram_purchase_id'));

            return true;
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    /**
     * Proses reject pengajuan
     */
    public function rejectPengajuan($request, $id)
    {
        $level = Auth::user()->position; //cek level
        $pengajuanPO = PesananPembelian::findOrFail($id);

        if ($level === 'direktur') {
            try {
                if ($pengajuanPO->grandtotal < 5000000) {
                    $pengajuanPO->update([
                        'catatan_direktur' => $request->input('catatan'),
                        'approve_direktur' => 2,
                        'approve_komisaris' => 2,
                        'direktur_id' => Auth::user()->id,
                        'komisaris_id' => Auth::user()->id,
                        'status_proses' => 0,
                    ]);
                } else {
                    $pengajuanPO->update([
                        'catatan_direktur' => $request->input('catatan'),
                        'approve_direktur' => 2,
                        'approve_komisaris' => 2,
                        'status_proses' => 2,
                    ]);
                }

                $this->logAktifitasService->createLog('Tidak menyetujui PO nomer : ' . $pengajuanPO->nomer_pesanan_pembelian);

                //kirim notif ke telegram
                $text = "<strong>PENGAJUAN PO</strong>\n\n"
                    . "Nomer : <strong>" . $pengajuanPO->nomer_pesanan_pembelian . "</strong>\n"
                    . "Tanggal : <strong>" . date('d-m-Y', strtotime($pengajuanPO->tanggal)) . "</strong>\n\n"
                    . "<strong>TIDAK DISETUJUI</strong> oleh " . Auth::user()->name . ".\n\n";

                SendNotifikasiJob::dispatchSync($text, config('telegram_config.v2_telegram_purchase_id'));

                return true;
            } catch (\Exception $e) {
                return $e->getMessage();
            }
        } else if ($level === 'komisaris') {
            try {
                $pengajuanPO->update([
                    'catatan_komisaris' => $request->input('catatan'),
                    'approve_komisaris' => 2,
                    'approve_direktur' => 2,
                    'status_proses' => 2,
                ]);

                $this->logAktifitasService->createLog('Tidak menyetujui PO nomer : ' . $pengajuanPO->nomer_pesanan_pembelian);

                //kirim notif ke telegram
                $text = "<strong>PENGAJUAN PO</strong>\n\n"
                    . "Nomer : <strong>" . $pengajuanPO->nomer_pesanan_pembelian . "</strong>\n"
                    . "Tanggal : <strong>" . date('d-m-Y', strtotime($pengajuanPO->tanggal)) . "</strong>\n\n"
                    . "<strong>TIDAK DISETUJUI</strong> oleh " . Auth::user()->name . ".\n\n";

                SendNotifikasiJob::dispatchSync($text, config('telegram_config.v2_telegram_purchase_id'));

                return true;
            } catch (\Exception $e) {
                return $e->getMessage();
            }
        }
    }
}
