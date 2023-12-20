<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\{
    Roleakses,
    User
};
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class RoleAksesController extends Controller
{
    public function index(){
        if(auth()->user()->cannot('viewAny', Roleakses::class)) abort('403', 'access denied');

        $user = User::where('id', '!=', 1)->get();
        return view('role-akses.index', compact('user'));
    }
    public function show($id){
        if(auth()->user()->cannot('view', Roleakses::class)) abort('403', 'access denied');

        $user = User::find($id);
        $role = Roleakses::where('user_id', $id)->get();
        return view('role-akses.show', compact('role','user'));
    }
    public function store_update($id){
        $request = Request();

        // Jurnal Voucher
        $jv_role = Roleakses::where(['user_id' => $id, 'nama_controller' => 'generalledger_jv'])->first();
        if($request->jv_i == 1 || $request->jv_c == 1 || $request->jv_r == 1 || $request->jv_u == 1 || $request->jv_d == 1){
            if(empty($jv_role)){
                $role = new Roleakses;
                $role->nama_controller = 'generalledger_jv';
                $role->user_id = $id;
                $role->can_index = (empty(Request('jv_i'))) ? 0 : 1;
                $role->can_create = (empty(Request('jv_c'))) ? 0 : 1;
                $role->can_read = (empty(Request('jv_r'))) ? 0 : 1;
                $role->can_update = (empty(Request('jv_u'))) ? 0 : 1;
                $role->can_delete = (empty(Request('jv_d'))) ? 0 : 1;
                $role->save();
            }else{
                $role = Roleakses::find($jv_role->id);
                $role->nama_controller = 'generalledger_jv';
                $role->user_id = $id;
                $role->can_index = (Request('jv_i') == 1) ? 1 : 0;
                $role->can_create = (Request('jv_c') == 1) ? 1 : 0;
                $role->can_read = (Request('jv_r') == 1) ? 1 : 0;
                $role->can_update = (Request('jv_u') == 1) ? 1 : 0;
                $role->can_delete = (Request('jv_d') == 1) ? 1 : 0;
                $role->save();
            }
        }else{
            if(!empty($jv_role)){
                $role = Roleakses::find($jv_role->id);
                $role->delete();
            }
        }

        // COA
        $coa_role = Roleakses::where(['user_id' => $id, 'nama_controller' => 'generalledger_coa'])->first();
        if($request->coa_i == 1 || $request->coa_c == 1 || $request->coa_r == 1 || $request->coa_u == 1 || $request->coa_d == 1){
            if(empty($coa_role)){
                $role = new Roleakses;
                $role->nama_controller = 'generalledger_coa';
                $role->user_id = $id;
                $role->can_index = (empty(Request('coa_i'))) ? 0 : 1;
                $role->can_create = (empty(Request('coa_c'))) ? 0 : 1;
                $role->can_read = (empty(Request('coa_r'))) ? 0 : 1;
                $role->can_update = (empty(Request('coa_u'))) ? 0 : 1;
                $role->can_delete = (empty(Request('coa_d'))) ? 0 : 1;
                $role->save();
            }else{
                $role = Roleakses::find($coa_role->id);
                $role->nama_controller = 'generalledger_coa';
                $role->user_id = $id;
                $role->can_index = (Request('coa_i') == 1) ? 1 : 0;
                $role->can_create = (Request('coa_c') == 1) ? 1 : 0;
                $role->can_read = (Request('coa_r') == 1) ? 1 : 0;
                $role->can_update = (Request('coa_u') == 1) ? 1 : 0;
                $role->can_delete = (Request('coa_d') == 1) ? 1 : 0;
                $role->save();
            }
        }else{
            if(!empty($coa_role)){
                $role = Roleakses::find($coa_role->id);
                $role->delete();
            }
        }

        // Deposite Payment Buku Bank
        $dp_role = Roleakses::where(['user_id' => $id, 'nama_controller' => 'cashbank'])->first();
        if($request->dp_i == 1 || $request->dp_c == 1 || $request->dp_r == 1 || $request->dp_u == 1 || $request->dp_d == 1){
            if(empty($dp_role)){
                $role = new Roleakses;
                $role->nama_controller = 'cashbank';
                $role->user_id = $id;
                $role->can_index = (empty(Request('dp_i'))) ? 0 : 1;
                $role->can_create = (empty(Request('dp_c'))) ? 0 : 1;
                $role->can_read = (empty(Request('dp_r'))) ? 0 : 1;
                $role->can_update = (empty(Request('dp_u'))) ? 0 : 1;
                $role->can_delete = (empty(Request('dp_d'))) ? 0 : 1;
                $role->save();
            }else{
                $role = Roleakses::find($dp_role->id);
                $role->nama_controller = 'cashbank';
                $role->user_id = $id;
                $role->can_index = (Request('dp_i') == 1) ? 1 : 0;
                $role->can_create = (Request('dp_c') == 1) ? 1 : 0;
                $role->can_read = (Request('dp_r') == 1) ? 1 : 0;
                $role->can_update = (Request('dp_u') == 1) ? 1 : 0;
                $role->can_delete = (Request('dp_d') == 1) ? 1 : 0;
                $role->save();
            }
        }else{
            if(!empty($dp_role)){
                $role = Roleakses::find($dp_role->id);
                $role->delete();
            }
        }

        // cashbank Buku Bank
        $bb_role = Roleakses::where(['user_id' => $id, 'nama_controller' => 'cashbank_bukubank'])->first();
        if($request->bb_i == 1 || $request->bb_c == 1 || $request->bb_r == 1 || $request->bb_u == 1 || $request->bb_d == 1){
            if(empty($bb_role)){
                $role = new Roleakses;
                $role->nama_controller = 'cashbank_bukubank';
                $role->user_id = $id;
                $role->can_index = (empty(Request('bb_i'))) ? 0 : 1;
                $role->can_create = (empty(Request('bb_c'))) ? 0 : 1;
                $role->can_read = (empty(Request('bb_r'))) ? 0 : 1;
                $role->can_update = (empty(Request('bb_u'))) ? 0 : 1;
                $role->can_delete = (empty(Request('bb_d'))) ? 0 : 1;
                $role->save();
            }else{
                $role = Roleakses::find($bb_role->id);
                $role->nama_controller = 'cashbank_bukubank';
                $role->user_id = $id;
                $role->can_index = (Request('bb_i') == 1) ? 1 : 0;
                $role->can_create = (Request('bb_c') == 1) ? 1 : 0;
                $role->can_read = (Request('bb_r') == 1) ? 1 : 0;
                $role->can_update = (Request('bb_u') == 1) ? 1 : 0;
                $role->can_delete = (Request('bb_d') == 1) ? 1 : 0;
                $role->save();
            }
        }else{
            if(!empty($bb_role)){
                $role = Roleakses::find($bb_role->id);
                $role->delete();
            }
        }

        // purchasing pr
        $pr_role = Roleakses::where(['user_id' => $id, 'nama_controller' => 'purchasing_pr'])->first();
        if($request->pr_i == 1 || $request->pr_c == 1 || $request->pr_r == 1 || $request->pr_u == 1 || $request->pr_d == 1){
            if(empty($pr_role)){
                $role = new Roleakses;
                $role->nama_controller = 'purchasing_pr';
                $role->user_id = $id;
                $role->can_index = (empty(Request('pr_i'))) ? 0 : 1;
                $role->can_create = (empty(Request('pr_c'))) ? 0 : 1;
                $role->can_read = (empty(Request('pr_r'))) ? 0 : 1;
                $role->can_update = (empty(Request('pr_u'))) ? 0 : 1;
                $role->can_delete = (empty(Request('pr_d'))) ? 0 : 1;
                $role->save();
            }else{
                $role = Roleakses::find($pr_role->id);
                $role->nama_controller = 'purchasing_pr';
                $role->user_id = $id;
                $role->can_index = (Request('pr_i') == 1) ? 1 : 0;
                $role->can_create = (Request('pr_c') == 1) ? 1 : 0;
                $role->can_read = (Request('pr_r') == 1) ? 1 : 0;
                $role->can_update = (Request('pr_u') == 1) ? 1 : 0;
                $role->can_delete = (Request('pr_d') == 1) ? 1 : 0;
                $role->save();
            }
        }else{
            if(!empty($pr_role)){
                $role = Roleakses::find($pr_role->id);
                $role->delete();
            }
        }

        // purchasing po
        $po_role = Roleakses::where(['user_id' => $id, 'nama_controller' => 'purchasing_po'])->first();
        if($request->po_i == 1 || $request->po_c == 1 || $request->po_r == 1 || $request->po_u == 1 || $request->po_d == 1){
            if(empty($po_role)){
                $role = new Roleakses;
                $role->nama_controller = 'purchasing_po';
                $role->user_id = $id;
                $role->can_index = (empty(Request('po_i'))) ? 0 : 1;
                $role->can_create = (empty(Request('po_c'))) ? 0 : 1;
                $role->can_read = (empty(Request('po_r'))) ? 0 : 1;
                $role->can_update = (empty(Request('po_u'))) ? 0 : 1;
                $role->can_delete = (empty(Request('po_d'))) ? 0 : 1;
                $role->save();
            }else{
                $role = Roleakses::find($po_role->id);
                $role->nama_controller = 'purchasing_po';
                $role->user_id = $id;
                $role->can_index = (Request('po_i') == 1) ? 1 : 0;
                $role->can_create = (Request('po_c') == 1) ? 1 : 0;
                $role->can_read = (Request('po_r') == 1) ? 1 : 0;
                $role->can_update = (Request('po_u') == 1) ? 1 : 0;
                $role->can_delete = (Request('po_d') == 1) ? 1 : 0;
                $role->save();
            }
        }else{
            if(!empty($po_role)){
                $role = Roleakses::find($po_role->id);
                $role->delete();
            }
        }

        // purchasing ri
        $ri_role = Roleakses::where(['user_id' => $id, 'nama_controller' => 'purchasing_ri'])->first();
        if($request->ri_i == 1 || $request->ri_c == 1 || $request->ri_r == 1 || $request->ri_u == 1 || $request->ri_d == 1){
            if(empty($ri_role)){
                $role = new Roleakses;
                $role->nama_controller = 'purchasing_ri';
                $role->user_id = $id;
                $role->can_index = (empty(Request('ri_i'))) ? 0 : 1;
                $role->can_create = (empty(Request('ri_c'))) ? 0 : 1;
                $role->can_read = (empty(Request('ri_r'))) ? 0 : 1;
                $role->can_update = (empty(Request('ri_u'))) ? 0 : 1;
                $role->can_delete = (empty(Request('ri_d'))) ? 0 : 1;
                $role->save();
            }else{
                $role = Roleakses::find($ri_role->id);
                $role->nama_controller = 'purchasing_ri';
                $role->user_id = $id;
                $role->can_index = (Request('ri_i') == 1) ? 1 : 0;
                $role->can_create = (Request('ri_c') == 1) ? 1 : 0;
                $role->can_read = (Request('ri_r') == 1) ? 1 : 0;
                $role->can_update = (Request('ri_u') == 1) ? 1 : 0;
                $role->can_delete = (Request('ri_d') == 1) ? 1 : 0;
                $role->save();
            }
        }else{
            if(!empty($ri_role)){
                $role = Roleakses::find($ri_role->id);
                $role->delete();
            }
        }

        // purchasing pi
        $pi_role = Roleakses::where(['user_id' => $id, 'nama_controller' => 'purchasing_pi'])->first();
        if($request->pi_i == 1 || $request->pi_c == 1 || $request->pi_r == 1 || $request->pi_u == 1 || $request->pi_d == 1){
            if(empty($pi_role)){
                $role = new Roleakses;
                $role->nama_controller = 'purchasing_pi';
                $role->user_id = $id;
                $role->can_index = (empty(Request('pi_i'))) ? 0 : 1;
                $role->can_create = (empty(Request('pi_c'))) ? 0 : 1;
                $role->can_read = (empty(Request('pi_r'))) ? 0 : 1;
                $role->can_update = (empty(Request('pi_u'))) ? 0 : 1;
                $role->can_delete = (empty(Request('pi_d'))) ? 0 : 1;
                $role->save();
            }else{
                $role = Roleakses::find($pi_role->id);
                $role->nama_controller = 'purchasing_pi';
                $role->user_id = $id;
                $role->can_index = (Request('pi_i') == 1) ? 1 : 0;
                $role->can_create = (Request('pi_c') == 1) ? 1 : 0;
                $role->can_read = (Request('pi_r') == 1) ? 1 : 0;
                $role->can_update = (Request('pi_u') == 1) ? 1 : 0;
                $role->can_delete = (Request('pi_d') == 1) ? 1 : 0;
                $role->save();
            }
        }else{
            if(!empty($pi_role)){
                $role = Roleakses::find($pi_role->id);
                $role->delete();
            }
        }

        // purchasing pp
        $pp_role = Roleakses::where(['user_id' => $id, 'nama_controller' => 'purchasing_pp'])->first();
        if($request->pp_i == 1 || $request->pp_c == 1 || $request->pp_r == 1 || $request->pp_u == 1 || $request->pp_d == 1){
            if(empty($pp_role)){
                $role = new Roleakses;
                $role->nama_controller = 'purchasing_pp';
                $role->user_id = $id;
                $role->can_index = (empty(Request('pp_i'))) ? 0 : 1;
                $role->can_create = (empty(Request('pp_c'))) ? 0 : 1;
                $role->can_read = (empty(Request('pp_r'))) ? 0 : 1;
                $role->can_update = (empty(Request('pp_u'))) ? 0 : 1;
                $role->can_delete = (empty(Request('pp_d'))) ? 0 : 1;
                $role->save();
            }else{
                $role = Roleakses::find($pp_role->id);
                $role->nama_controller = 'purchasing_pp';
                $role->user_id = $id;
                $role->can_index = (Request('pp_i') == 1) ? 1 : 0;
                $role->can_create = (Request('pp_c') == 1) ? 1 : 0;
                $role->can_read = (Request('pp_r') == 1) ? 1 : 0;
                $role->can_update = (Request('pp_u') == 1) ? 1 : 0;
                $role->can_delete = (Request('pp_d') == 1) ? 1 : 0;
                $role->save();
            }
        }else{
            if(!empty($pp_role)){
                $role = Roleakses::find($pp_role->id);
                $role->delete();
            }
        }

        // sales so
        $so_role = Roleakses::where(['user_id' => $id, 'nama_controller' => 'sales_so'])->first();
        if($request->so_i == 1 || $request->so_c == 1 || $request->so_r == 1 || $request->so_u == 1 || $request->so_d == 1){
            if(empty($so_role)){
                $role = new Roleakses;
                $role->nama_controller = 'sales_so';
                $role->user_id = $id;
                $role->can_index = (empty(Request('so_i'))) ? 0 : 1;
                $role->can_create = (empty(Request('so_c'))) ? 0 : 1;
                $role->can_read = (empty(Request('so_r'))) ? 0 : 1;
                $role->can_update = (empty(Request('so_u'))) ? 0 : 1;
                $role->can_delete = (empty(Request('so_d'))) ? 0 : 1;
                $role->save();
            }else{
                $role = Roleakses::find($so_role->id);
                $role->nama_controller = 'sales_so';
                $role->user_id = $id;
                $role->can_index = (Request('so_i') == 1) ? 1 : 0;
                $role->can_create = (Request('so_c') == 1) ? 1 : 0;
                $role->can_read = (Request('so_r') == 1) ? 1 : 0;
                $role->can_update = (Request('so_u') == 1) ? 1 : 0;
                $role->can_delete = (Request('so_d') == 1) ? 1 : 0;
                $role->save();
            }
        }else{
            if(!empty($so_role)){
                $role = Roleakses::find($so_role->id);
                $role->delete();
            }
        }

        // sales do
        $do_role = Roleakses::where(['user_id' => $id, 'nama_controller' => 'sales_do'])->first();
        if($request->do_i == 1 || $request->do_c == 1 || $request->do_r == 1 || $request->do_u == 1 || $request->do_d == 1){
            if(empty($do_role)){
                $role = new Roleakses;
                $role->nama_controller = 'sales_do';
                $role->user_id = $id;
                $role->can_index = (empty(Request('do_i'))) ? 0 : 1;
                $role->can_create = (empty(Request('do_c'))) ? 0 : 1;
                $role->can_read = (empty(Request('do_r'))) ? 0 : 1;
                $role->can_update = (empty(Request('do_u'))) ? 0 : 1;
                $role->can_delete = (empty(Request('do_d'))) ? 0 : 1;
                $role->save();
            }else{
                $role = Roleakses::find($do_role->id);
                $role->nama_controller = 'sales_do';
                $role->user_id = $id;
                $role->can_index = (Request('do_i') == 1) ? 1 : 0;
                $role->can_create = (Request('do_c') == 1) ? 1 : 0;
                $role->can_read = (Request('do_r') == 1) ? 1 : 0;
                $role->can_update = (Request('do_u') == 1) ? 1 : 0;
                $role->can_delete = (Request('do_d') == 1) ? 1 : 0;
                $role->save();
            }
        }else{
            if(!empty($do_role)){
                $role = Roleakses::find($do_role->id);
                $role->delete();
            }
        }
        
        // sales si
        $si_role = Roleakses::where(['user_id' => $id, 'nama_controller' => 'sales_si'])->first();
        if($request->si_i == 1 || $request->si_c == 1 || $request->si_r == 1 || $request->si_u == 1 || $request->si_d == 1){
            if(empty($si_role)){
                $role = new Roleakses;
                $role->nama_controller = 'sales_si';
                $role->user_id = $id;
                $role->can_index = (empty(Request('si_i'))) ? 0 : 1;
                $role->can_create = (empty(Request('si_c'))) ? 0 : 1;
                $role->can_read = (empty(Request('si_r'))) ? 0 : 1;
                $role->can_update = (empty(Request('si_u'))) ? 0 : 1;
                $role->can_delete = (empty(Request('si_d'))) ? 0 : 1;
                $role->save();
            }else{
                $role = Roleakses::find($si_role->id);
                $role->nama_controller = 'sales_si';
                $role->user_id = $id;
                $role->can_index = (Request('si_i') == 1) ? 1 : 0;
                $role->can_create = (Request('si_c') == 1) ? 1 : 0;
                $role->can_read = (Request('si_r') == 1) ? 1 : 0;
                $role->can_update = (Request('si_u') == 1) ? 1 : 0;
                $role->can_delete = (Request('si_d') == 1) ? 1 : 0;
                $role->save();
            }
        }else{
            if(!empty($si_role)){
                $role = Roleakses::find($si_role->id);
                $role->delete();
            }
        }
        
        // sales cr
        $cr_role = Roleakses::where(['user_id' => $id, 'nama_controller' => 'sales_cr'])->first();
        if($request->cr_i == 1 || $request->cr_c == 1 || $request->cr_r == 1 || $request->cr_u == 1 || $request->cr_d == 1){
            if(empty($cr_role)){
                $role = new Roleakses;
                $role->nama_controller = 'sales_cr';
                $role->user_id = $id;
                $role->can_index = (empty(Request('cr_i'))) ? 0 : 1;
                $role->can_create = (empty(Request('cr_c'))) ? 0 : 1;
                $role->can_read = (empty(Request('cr_r'))) ? 0 : 1;
                $role->can_update = (empty(Request('cr_u'))) ? 0 : 1;
                $role->can_delete = (empty(Request('cr_d'))) ? 0 : 1;
                $role->save();
            }else{
                $role = Roleakses::find($cr_role->id);
                $role->nama_controller = 'sales_cr';
                $role->user_id = $id;
                $role->can_index = (Request('cr_i') == 1) ? 1 : 0;
                $role->can_create = (Request('cr_c') == 1) ? 1 : 0;
                $role->can_read = (Request('cr_r') == 1) ? 1 : 0;
                $role->can_update = (Request('cr_u') == 1) ? 1 : 0;
                $role->can_delete = (Request('cr_d') == 1) ? 1 : 0;
                $role->save();
            }
        }else{
            if(!empty($cr_role)){
                $role = Roleakses::find($cr_role->id);
                $role->delete();
            }
        }

        // Inventory li
        $li_role = Roleakses::where(['user_id' => $id, 'nama_controller' => 'inventory_li'])->first();
        if($request->li_i == 1 || $request->li_c == 1 || $request->li_r == 1 || $request->li_u == 1 || $request->li_d == 1){
            if(empty($li_role)){
                $role = new Roleakses;
                $role->nama_controller = 'inventory_li';
                $role->user_id = $id;
                $role->can_index = (empty(Request('li_i'))) ? 0 : 1;
                $role->can_create = (empty(Request('li_c'))) ? 0 : 1;
                $role->can_read = (empty(Request('li_r'))) ? 0 : 1;
                $role->can_update = (empty(Request('li_u'))) ? 0 : 1;
                $role->can_delete = (empty(Request('li_d'))) ? 0 : 1;
                $role->save();
            }else{
                $role = Roleakses::find($li_role->id);
                $role->nama_controller = 'inventory_li';
                $role->user_id = $id;
                $role->can_index = (Request('li_i') == 1) ? 1 : 0;
                $role->can_create = (Request('li_c') == 1) ? 1 : 0;
                $role->can_read = (Request('li_r') == 1) ? 1 : 0;
                $role->can_update = (Request('li_u') == 1) ? 1 : 0;
                $role->can_delete = (Request('li_d') == 1) ? 1 : 0;
                $role->save();
            }
        }else{
            if(!empty($li_role)){
                $role = Roleakses::find($li_role->id);
                $role->delete();
            }
        }
        
        // Inventory mt
        $mt_role = Roleakses::where(['user_id' => $id, 'nama_controller' => 'inventory_mt'])->first();
        if($request->mt_i == 1 || $request->mt_c == 1 || $request->mt_r == 1 || $request->mt_u == 1 || $request->mt_d == 1){
            if(empty($mt_role)){
                $role = new Roleakses;
                $role->nama_controller = 'inventory_mt';
                $role->user_id = $id;
                $role->can_index = (empty(Request('mt_i'))) ? 0 : 1;
                $role->can_create = (empty(Request('mt_c'))) ? 0 : 1;
                $role->can_read = (empty(Request('mt_r'))) ? 0 : 1;
                $role->can_update = (empty(Request('mt_u'))) ? 0 : 1;
                $role->can_delete = (empty(Request('mt_d'))) ? 0 : 1;
                $role->save();
            }else{
                $role = Roleakses::find($mt_role->id);
                $role->nama_controller = 'inventory_mt';
                $role->user_id = $id;
                $role->can_index = (Request('mt_i') == 1) ? 1 : 0;
                $role->can_create = (Request('mt_c') == 1) ? 1 : 0;
                $role->can_read = (Request('mt_r') == 1) ? 1 : 0;
                $role->can_update = (Request('mt_u') == 1) ? 1 : 0;
                $role->can_delete = (Request('mt_d') == 1) ? 1 : 0;
                $role->save();
            }
        }else{
            if(!empty($mt_role)){
                $role = Roleakses::find($mt_role->id);
                $role->delete();
            }
        }

        // Inventory st
        $st_role = Roleakses::where(['user_id' => $id, 'nama_controller' => 'inventory_st'])->first();
        if($request->st_i == 1 || $request->st_c == 1 || $request->st_r == 1 || $request->st_u == 1 || $request->st_d == 1){
            if(empty($st_role)){
                $role = new Roleakses;
                $role->nama_controller = 'inventory_st';
                $role->user_id = $id;
                $role->can_index = (empty(Request('st_i'))) ? 0 : 1;
                $role->can_create = (empty(Request('st_c'))) ? 0 : 1;
                $role->can_read = (empty(Request('st_r'))) ? 0 : 1;
                $role->can_update = (empty(Request('st_u'))) ? 0 : 1;
                $role->can_delete = (empty(Request('st_d'))) ? 0 : 1;
                $role->save();
            }else{
                $role = Roleakses::find($st_role->id);
                $role->nama_controller = 'inventory_st';
                $role->user_id = $id;
                $role->can_index = (Request('st_i') == 1) ? 1 : 0;
                $role->can_create = (Request('st_c') == 1) ? 1 : 0;
                $role->can_read = (Request('st_r') == 1) ? 1 : 0;
                $role->can_update = (Request('st_u') == 1) ? 1 : 0;
                $role->can_delete = (Request('st_d') == 1) ? 1 : 0;
                $role->save();
            }
        }else{
            if(!empty($st_role)){
                $role = Roleakses::find($st_role->id);
                $role->delete();
            }
        }

        // category it
        $it_role = Roleakses::where(['user_id' => $id, 'nama_controller' => 'category_it'])->first();
        if($request->it_i == 1 || $request->it_c == 1 || $request->it_r == 1 || $request->it_u == 1 || $request->it_d == 1){
            if(empty($it_role)){
                $role = new Roleakses;
                $role->nama_controller = 'category_it';
                $role->user_id = $id;
                $role->can_index = (empty(Request('it_i'))) ? 0 : 1;
                $role->can_create = (empty(Request('it_c'))) ? 0 : 1;
                $role->can_read = (empty(Request('it_r'))) ? 0 : 1;
                $role->can_update = (empty(Request('it_u'))) ? 0 : 1;
                $role->can_delete = (empty(Request('it_d'))) ? 0 : 1;
                $role->save();
            }else{
                $role = Roleakses::find($it_role->id);
                $role->nama_controller = 'category_it';
                $role->user_id = $id;
                $role->can_index = (Request('it_i') == 1) ? 1 : 0;
                $role->can_create = (Request('it_c') == 1) ? 1 : 0;
                $role->can_read = (Request('it_r') == 1) ? 1 : 0;
                $role->can_update = (Request('it_u') == 1) ? 1 : 0;
                $role->can_delete = (Request('it_d') == 1) ? 1 : 0;
                $role->save();
            }
        }else{
            if(!empty($it_role)){
                $role = Roleakses::find($it_role->id);
                $role->delete();
            }
        }

        // category cs
        $cs_role = Roleakses::where(['user_id' => $id, 'nama_controller' => 'category_cs'])->first();
        if($request->cs_i == 1 || $request->cs_c == 1 || $request->cs_r == 1 || $request->cs_u == 1 || $request->cs_d == 1){
            if(empty($cs_role)){
                $role = new Roleakses;
                $role->nama_controller = 'category_cs';
                $role->user_id = $id;
                $role->can_index = (empty(Request('cs_i'))) ? 0 : 1;
                $role->can_create = (empty(Request('cs_c'))) ? 0 : 1;
                $role->can_read = (empty(Request('cs_r'))) ? 0 : 1;
                $role->can_update = (empty(Request('cs_u'))) ? 0 : 1;
                $role->can_delete = (empty(Request('cs_d'))) ? 0 : 1;
                $role->save();
            }else{
                $role = Roleakses::find($cs_role->id);
                $role->nama_controller = 'category_cs';
                $role->user_id = $id;
                $role->can_index = (Request('cs_i') == 1) ? 1 : 0;
                $role->can_create = (Request('cs_c') == 1) ? 1 : 0;
                $role->can_read = (Request('cs_r') == 1) ? 1 : 0;
                $role->can_update = (Request('cs_u') == 1) ? 1 : 0;
                $role->can_delete = (Request('cs_d') == 1) ? 1 : 0;
                $role->save();
            }
        }else{
            if(!empty($cs_role)){
                $role = Roleakses::find($cs_role->id);
                $role->delete();
            }
        }

        // category su
        $su_role = Roleakses::where(['user_id' => $id, 'nama_controller' => 'category_su'])->first();
        if($request->su_i == 1 || $request->su_c == 1 || $request->su_r == 1 || $request->su_u == 1 || $request->su_d == 1){
            if(empty($su_role)){
                $role = new Roleakses;
                $role->nama_controller = 'category_su';
                $role->user_id = $id;
                $role->can_index = (empty(Request('su_i'))) ? 0 : 1;
                $role->can_create = (empty(Request('su_c'))) ? 0 : 1;
                $role->can_read = (empty(Request('su_r'))) ? 0 : 1;
                $role->can_update = (empty(Request('su_u'))) ? 0 : 1;
                $role->can_delete = (empty(Request('su_d'))) ? 0 : 1;
                $role->save();
            }else{
                $role = Roleakses::find($su_role->id);
                $role->nama_controller = 'category_su';
                $role->user_id = $id;
                $role->can_index = (Request('su_i') == 1) ? 1 : 0;
                $role->can_create = (Request('su_c') == 1) ? 1 : 0;
                $role->can_read = (Request('su_r') == 1) ? 1 : 0;
                $role->can_update = (Request('su_u') == 1) ? 1 : 0;
                $role->can_delete = (Request('su_d') == 1) ? 1 : 0;
                $role->save();
            }
        }else{
            if(!empty($su_role)){
                $role = Roleakses::find($su_role->id);
                $role->delete();
            }
        }

        // hrga employee
        $employee_role = Roleakses::where(['user_id' => $id, 'nama_controller' => 'hrga_employee'])->first();
        if($request->employee_i == 1 || $request->employee_c == 1 || $request->employee_r == 1 || $request->employee_u == 1 || $request->employee_d == 1){
            if(empty($employee_role)){
                $role = new Roleakses;
                $role->nama_controller = 'hrga_employee';
                $role->user_id = $id;
                $role->can_index = (empty(Request('employee_i'))) ? 0 : 1;
                $role->can_create = (empty(Request('employee_c'))) ? 0 : 1;
                $role->can_read = (empty(Request('employee_r'))) ? 0 : 1;
                $role->can_update = (empty(Request('employee_u'))) ? 0 : 1;
                $role->can_delete = (empty(Request('employee_d'))) ? 0 : 1;
                $role->save();
            }else{
                $role = Roleakses::find($employee_role->id);
                $role->nama_controller = 'hrga_employee';
                $role->user_id = $id;
                $role->can_index = (Request('employee_i') == 1) ? 1 : 0;
                $role->can_create = (Request('employee_c') == 1) ? 1 : 0;
                $role->can_read = (Request('employee_r') == 1) ? 1 : 0;
                $role->can_update = (Request('employee_u') == 1) ? 1 : 0;
                $role->can_delete = (Request('employee_d') == 1) ? 1 : 0;
                $role->save();
            }
        }else{
            if(!empty($employee_role)){
                $role = Roleakses::find($employee_role->id);
                $role->delete();
            }
        }
        
        // hrga asset
        $asset_role = Roleakses::where(['user_id' => $id, 'nama_controller' => 'hrga_asset'])->first();
        if($request->asset_i == 1 || $request->asset_c == 1 || $request->asset_r == 1 || $request->asset_u == 1 || $request->asset_d == 1){
            if(empty($asset_role)){
                $role = new Roleakses;
                $role->nama_controller = 'hrga_asset';
                $role->user_id = $id;
                $role->can_index = (empty(Request('asset_i'))) ? 0 : 1;
                $role->can_create = (empty(Request('asset_c'))) ? 0 : 1;
                $role->can_read = (empty(Request('asset_r'))) ? 0 : 1;
                $role->can_update = (empty(Request('asset_u'))) ? 0 : 1;
                $role->can_delete = (empty(Request('asset_d'))) ? 0 : 1;
                $role->save();
            }else{
                $role = Roleakses::find($asset_role->id);
                $role->nama_controller = 'hrga_asset';
                $role->user_id = $id;
                $role->can_index = (Request('asset_i') == 1) ? 1 : 0;
                $role->can_create = (Request('asset_c') == 1) ? 1 : 0;
                $role->can_read = (Request('asset_r') == 1) ? 1 : 0;
                $role->can_update = (Request('asset_u') == 1) ? 1 : 0;
                $role->can_delete = (Request('asset_d') == 1) ? 1 : 0;
                $role->save();
            }
        }else{
            if(!empty($asset_role)){
                $role = Roleakses::find($asset_role->id);
                $role->delete();
            }
        }
        
        // hrga category_asset
        $category_asset_role = Roleakses::where(['user_id' => $id, 'nama_controller' => 'hrga_category_asset'])->first();
        if($request->category_asset_i == 1 || $request->category_asset_c == 1 || $request->category_asset_r == 1 || $request->category_asset_u == 1 || $request->category_asset_d == 1){
            if(empty($category_asset_role)){
                $role = new Roleakses;
                $role->nama_controller = 'hrga_category_asset';
                $role->user_id = $id;
                $role->can_index = (empty(Request('category_asset_i'))) ? 0 : 1;
                $role->can_create = (empty(Request('category_asset_c'))) ? 0 : 1;
                $role->can_read = (empty(Request('category_asset_r'))) ? 0 : 1;
                $role->can_update = (empty(Request('category_asset_u'))) ? 0 : 1;
                $role->can_delete = (empty(Request('category_asset_d'))) ? 0 : 1;
                $role->save();
            }else{
                $role = Roleakses::find($category_asset_role->id);
                $role->nama_controller = 'hrga_category_asset';
                $role->user_id = $id;
                $role->can_index = (Request('category_asset_i') == 1) ? 1 : 0;
                $role->can_create = (Request('category_asset_c') == 1) ? 1 : 0;
                $role->can_read = (Request('category_asset_r') == 1) ? 1 : 0;
                $role->can_update = (Request('category_asset_u') == 1) ? 1 : 0;
                $role->can_delete = (Request('category_asset_d') == 1) ? 1 : 0;
                $role->save();
            }
        }else{
            if(!empty($category_asset_role)){
                $role = Roleakses::find($category_asset_role->id);
                $role->delete();
            }
        }

        // master item
        $item_role = Roleakses::where(['user_id' => $id, 'nama_controller' => 'master_it'])->first();
        if($request->item_i == 1 || $request->item_c == 1 || $request->item_r == 1 || $request->item_u == 1 || $request->item_d == 1){
            if(empty($item_role)){
                $role = new Roleakses;
                $role->nama_controller = 'master_it';
                $role->user_id = $id;
                $role->can_index = (empty(Request('item_i'))) ? 0 : 1;
                $role->can_create = (empty(Request('item_c'))) ? 0 : 1;
                $role->can_read = (empty(Request('item_r'))) ? 0 : 1;
                $role->can_update = (empty(Request('item_u'))) ? 0 : 1;
                $role->can_delete = (empty(Request('item_d'))) ? 0 : 1;
                $role->save();
            }else{
                $role = Roleakses::find($item_role->id);
                $role->nama_controller = 'master_it';
                $role->user_id = $id;
                $role->can_index = (Request('item_i') == 1) ? 1 : 0;
                $role->can_create = (Request('item_c') == 1) ? 1 : 0;
                $role->can_read = (Request('item_r') == 1) ? 1 : 0;
                $role->can_update = (Request('item_u') == 1) ? 1 : 0;
                $role->can_delete = (Request('item_d') == 1) ? 1 : 0;
                $role->save();
            }
        }else{
            if(!empty($item_role)){
                $role = Roleakses::find($item_role->id);
                $role->delete();
            }
        }
        // master packet
        $packet_role = Roleakses::where(['user_id' => $id, 'nama_controller' => 'master_pck'])->first();
        if($request->packet_i == 1 || $request->packet_c == 1 || $request->packet_r == 1 || $request->packet_u == 1 || $request->packet_d == 1){
            if(empty($packet_role)){
                $role = new Roleakses;
                $role->nama_controller = 'master_pck';
                $role->user_id = $id;
                $role->can_index = (empty(Request('packet_i'))) ? 0 : 1;
                $role->can_create = (empty(Request('packet_c'))) ? 0 : 1;
                $role->can_read = (empty(Request('packet_r'))) ? 0 : 1;
                $role->can_update = (empty(Request('packet_u'))) ? 0 : 1;
                $role->can_delete = (empty(Request('packet_d'))) ? 0 : 1;
                $role->save();
            }else{
                $role = Roleakses::find($packet_role->id);
                $role->nama_controller = 'master_pck';
                $role->user_id = $id;
                $role->can_index = (Request('packet_i') == 1) ? 1 : 0;
                $role->can_create = (Request('packet_c') == 1) ? 1 : 0;
                $role->can_read = (Request('packet_r') == 1) ? 1 : 0;
                $role->can_update = (Request('packet_u') == 1) ? 1 : 0;
                $role->can_delete = (Request('packet_d') == 1) ? 1 : 0;
                $role->save();
            }
        }else{
            if(!empty($packet_role)){
                $role = Roleakses::find($packet_role->id);
                $role->delete();
            }
        }
        // master wr
        $wr_role = Roleakses::where(['user_id' => $id, 'nama_controller' => 'master_wr'])->first();
        if($request->wr_i == 1 || $request->wr_c == 1 || $request->wr_r == 1 || $request->wr_u == 1 || $request->wr_d == 1){
            if(empty($wr_role)){
                $role = new Roleakses;
                $role->nama_controller = 'master_wr';
                $role->user_id = $id;
                $role->can_index = (empty(Request('wr_i'))) ? 0 : 1;
                $role->can_create = (empty(Request('wr_c'))) ? 0 : 1;
                $role->can_read = (empty(Request('wr_r'))) ? 0 : 1;
                $role->can_update = (empty(Request('wr_u'))) ? 0 : 1;
                $role->can_delete = (empty(Request('wr_d'))) ? 0 : 1;
                $role->save();
            }else{
                $role = Roleakses::find($wr_role->id);
                $role->nama_controller = 'master_wr';
                $role->user_id = $id;
                $role->can_index = (Request('wr_i') == 1) ? 1 : 0;
                $role->can_create = (Request('wr_c') == 1) ? 1 : 0;
                $role->can_read = (Request('wr_r') == 1) ? 1 : 0;
                $role->can_update = (Request('wr_u') == 1) ? 1 : 0;
                $role->can_delete = (Request('wr_d') == 1) ? 1 : 0;
                $role->save();
            }
        }else{
            if(!empty($wr_role)){
                $role = Roleakses::find($wr_role->id);
                $role->delete();
            }
        }
        // master cs
        $cust_role = Roleakses::where(['user_id' => $id, 'nama_controller' => 'master_cs'])->first();
        if($request->cust_i == 1 || $request->cust_c == 1 || $request->cust_r == 1 || $request->cust_u == 1 || $request->cust_d == 1){
            if(empty($cust_role)){
                $role = new Roleakses;
                $role->nama_controller = 'master_cs';
                $role->user_id = $id;
                $role->can_index = (empty(Request('cust_i'))) ? 0 : 1;
                $role->can_create = (empty(Request('cust_c'))) ? 0 : 1;
                $role->can_read = (empty(Request('cust_r'))) ? 0 : 1;
                $role->can_update = (empty(Request('cust_u'))) ? 0 : 1;
                $role->can_delete = (empty(Request('cust_d'))) ? 0 : 1;
                $role->save();
            }else{
                $role = Roleakses::find($cust_role->id);
                $role->nama_controller = 'master_cs';
                $role->user_id = $id;
                $role->can_index = (Request('cust_i') == 1) ? 1 : 0;
                $role->can_create = (Request('cust_c') == 1) ? 1 : 0;
                $role->can_read = (Request('cust_r') == 1) ? 1 : 0;
                $role->can_update = (Request('cust_u') == 1) ? 1 : 0;
                $role->can_delete = (Request('cust_d') == 1) ? 1 : 0;
                $role->save();
            }
        }else{
            if(!empty($cust_role)){
                $role = Roleakses::find($cust_role->id);
                $role->delete();
            }
        }
        // master sls
        $sls_role = Roleakses::where(['user_id' => $id, 'nama_controller' => 'master_sl'])->first();
        if($request->sls_i == 1 || $request->sls_c == 1 || $request->sls_r == 1 || $request->sls_u == 1 || $request->sls_d == 1){
            if(empty($sls_role)){
                $role = new Roleakses;
                $role->nama_controller = 'master_sl';
                $role->user_id = $id;
                $role->can_index = (empty(Request('sls_i'))) ? 0 : 1;
                $role->can_create = (empty(Request('sls_c'))) ? 0 : 1;
                $role->can_read = (empty(Request('sls_r'))) ? 0 : 1;
                $role->can_update = (empty(Request('sls_u'))) ? 0 : 1;
                $role->can_delete = (empty(Request('sls_d'))) ? 0 : 1;
                $role->save();
            }else{
                $role = Roleakses::find($sls_role->id);
                $role->nama_controller = 'master_sl';
                $role->user_id = $id;
                $role->can_index = (Request('sls_i') == 1) ? 1 : 0;
                $role->can_create = (Request('sls_c') == 1) ? 1 : 0;
                $role->can_read = (Request('sls_r') == 1) ? 1 : 0;
                $role->can_update = (Request('sls_u') == 1) ? 1 : 0;
                $role->can_delete = (Request('sls_d') == 1) ? 1 : 0;
                $role->save();
            }
        }else{
            if(!empty($sls_role)){
                $role = Roleakses::find($sls_role->id);
                $role->delete();
            }
        }
        // master sp
        $sp_role = Roleakses::where(['user_id' => $id, 'nama_controller' => 'master_sp'])->first();
        if($request->sp_i == 1 || $request->sp_c == 1 || $request->sp_r == 1 || $request->sp_u == 1 || $request->sp_d == 1){
            if(empty($sp_role)){
                $role = new Roleakses;
                $role->nama_controller = 'master_sp';
                $role->user_id = $id;
                $role->can_index = (empty(Request('sp_i'))) ? 0 : 1;
                $role->can_create = (empty(Request('sp_c'))) ? 0 : 1;
                $role->can_read = (empty(Request('sp_r'))) ? 0 : 1;
                $role->can_update = (empty(Request('sp_u'))) ? 0 : 1;
                $role->can_delete = (empty(Request('sp_d'))) ? 0 : 1;
                $role->save();
            }else{
                $role = Roleakses::find($sp_role->id);
                $role->nama_controller = 'master_sp';
                $role->user_id = $id;
                $role->can_index = (Request('sp_i') == 1) ? 1 : 0;
                $role->can_create = (Request('sp_c') == 1) ? 1 : 0;
                $role->can_read = (Request('sp_r') == 1) ? 1 : 0;
                $role->can_update = (Request('sp_u') == 1) ? 1 : 0;
                $role->can_delete = (Request('sp_d') == 1) ? 1 : 0;
                $role->save();
            }
        }else{
            if(!empty($sp_role)){
                $role = Roleakses::find($sp_role->id);
                $role->delete();
            }
        }
        // role akses
        $rm_role = Roleakses::where(['user_id' => $id, 'nama_controller' => 'role_akses'])->first();
        if($request->rm_i == 1 || $request->rm_c == 1 || $request->rm_r == 1 || $request->rm_u == 1 || $request->rm_d == 1){
            if(empty($rm_role)){
                $role = new Roleakses;
                $role->nama_controller = 'role_akses';
                $role->user_id = $id;
                $role->can_index = (empty(Request('rm_i'))) ? 0 : 1;
                $role->can_create = (empty(Request('rm_c'))) ? 0 : 1;
                $role->can_read = (empty(Request('rm_r'))) ? 0 : 1;
                $role->can_update = (empty(Request('rm_u'))) ? 0 : 1;
                $role->can_delete = (empty(Request('rm_d'))) ? 0 : 1;
                $role->save();
            }else{
                $role = Roleakses::find($rm_role->id);
                $role->nama_controller = 'role_akses';
                $role->user_id = $id;
                $role->can_index = (Request('rm_i') == 1) ? 1 : 0;
                $role->can_create = (Request('rm_c') == 1) ? 1 : 0;
                $role->can_read = (Request('rm_r') == 1) ? 1 : 0;
                $role->can_update = (Request('rm_u') == 1) ? 1 : 0;
                $role->can_delete = (Request('rm_d') == 1) ? 1 : 0;
                $role->save();
            }
        }else{
            if(!empty($rm_role)){
                $role = Roleakses::find($rm_role->id);
                $role->delete();
            }
        }

        $so_check = Roleakses::where(['user_id' => $id, 'nama_controller' => 'btn_acc_checked'])->first();
            if($request->btn_check_so == 1){
            if(empty($so_check)){
                $role = new Roleakses;
                $role->nama_controller = 'btn_acc_checked';
                $role->user_id = $id;
                $role->can_index = (empty(Request('btn_check_so'))) ? 0 : 1;
                $role->can_create = 0;
                $role->can_read = 0;
                $role->can_update = 0;
                $role->can_delete = 0;
                $role->save();
            }else{
                $role = Roleakses::find($so_check->id);
                $role->nama_controller = 'btn_acc_checked';
                $role->user_id = $id;
                $role->can_index = (Request('btn_check_so') == 1) ? 1 : 0;
                $role->can_create = 0;
                $role->can_read = 0;
                $role->can_update = 0;
                $role->can_delete = 0;
                $role->save();
            }
        }else{
            if(!empty($so_check)){
                $role = Roleakses::find($so_check->id);
                $role->delete();
            }
        }



        // dd(Request());
        return redirect()->back();
    }
}
