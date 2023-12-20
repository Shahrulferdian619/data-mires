<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Packet;
use App\Models\Barang;
use App\Models\PacketRinci;

use Illuminate\Support\Facades\Gate;

class PacketController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(auth()->user()->cannot('viewAny', Packet::class)) abort('403', 'access denied');
        //
        $packet = Packet::where('status', 1)->get();
        return view('barang.packet.index', compact('packet'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        
        if(auth()->user()->cannot('create', Packet::class)) abort('403', 'access denied');
        $barang = Barang::where('type', 1)->get();
        return view('barang.packet.create' ,compact('barang'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        // dd($request->barang_id);

        if(auth()->user()->cannot('create', Packet::class)) abort('403', 'access denied');
        $request->validate([
            'nama_paket' => 'required'
        ]);

        $packet = new Packet;
        $packet->packet_name = $request->nama_paket;
        $packet->note = $request->keterangan;
        $packet->status = 1;
        $total = 0;
        for($j = 0; $j < count($request->id_barang); $j++){
            $total += $request->harga[$j] * $request->qty[$j];
        }
        $packet->total = $total;
        $packet->save();

        for($i = 0; $i < count($request->id_barang); $i++){
            $packet_rinci = new PacketRinci;
            $packet_rinci->id_packet = $packet->id;
            $packet_rinci->id_barang = $request->id_barang[$i];
            $packet_rinci->qty = $request->qty[$i];
            $packet_rinci->harga = $request->harga[$i];
            $packet_rinci->save();
        }

        return redirect('/admin/packet')->with('success', 'Berhasil membuat paket');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if(auth()->user()->cannot('view', Packet::class)) abort('403', 'access denied');
        //
        $packet = Packet::find($id);
        $packet_rinci = PacketRinci::where('id_packet', $id)->get(); 
        return view('barang.packet.show', compact('packet', 'packet_rinci'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
        
        if(auth()->user()->cannot('update', Packet::class)) abort('403', 'access denied');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        
        if(auth()->user()->cannot('delete', Packet::class)) abort('403', 'access denied');
        // dd($id);
        PacketRinci::where('id_packet', $id)->delete();
        $packet = Packet::find($id);
        $packet->delete();

        return redirect('/admin/packet')->with('success', 'Berhasil menghapus paket');
    }
}
