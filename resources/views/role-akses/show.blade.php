@extends('layouts.vuexy')

@section('header')
Role Access Management ( Manajemen Akses )
@endsection

@section('content')
@if($errors->all())
    @include('layouts.validation')
@elseif(session('success'))
    @include('layouts.success')
@endif
<div class="card">
    <div class="card-header">
        <h3>{{ $user->name }} <small>({{ $user->level->nama_level }})</small> </h3>
    </div>
    <div class="card-body">
        <form action="{{ url('admin/role-akses/'.$user->id) }}" method="post">
        <div class="table-responsive">
            <table class="table">
                <tr>
                    <td class="bg-light" colspan="6"><strong>General Ledger</strong> </td>
                </tr>
                <tr>
                    <td>Journal Voucher</td>
                    <td><label class="label"> <input <?php foreach($role as $akses){ if($akses->nama_controller == 'generalledger_jv' && $akses->can_index == 1){ echo 'checked'; } } ?> type="checkbox" value="1" name="jv_i"> Index</label></td>
                    <td><label class="label"> <input <?php foreach($role as $akses){ if($akses->nama_controller == 'generalledger_jv' && $akses->can_create == 1){ echo 'checked'; } } ?> type="checkbox" value="1" name="jv_c"> Create</label></td>
                    <td><label class="label"> <input <?php foreach($role as $akses){ if($akses->nama_controller == 'generalledger_jv' && $akses->can_read == 1){ echo 'checked'; } } ?> type="checkbox" value="1" name="jv_r"> Read</label></td>
                    <td><label class="label"> <input <?php foreach($role as $akses){ if($akses->nama_controller == 'generalledger_jv' && $akses->can_update == 1){ echo 'checked'; } } ?> type="checkbox" value="1" name="jv_u"> Update</label></td>
                    <td><label class="label"> <input <?php foreach($role as $akses){ if($akses->nama_controller == 'generalledger_jv' && $akses->can_delete == 1){ echo 'checked'; } } ?> type="checkbox" value="1" name="jv_d"> Delete</label></td>
                </tr>
                <tr>
                    <td>List Account (COA)</td>
                    <td><label class="label"> <input <?php foreach($role as $akses){ if($akses->nama_controller == 'generalledger_coa' && $akses->can_index == 1){ echo 'checked'; } } ?> type="checkbox" value="1" name="coa_i"> Index</label></td>
                    <td><label class="label"> <input <?php foreach($role as $akses){ if($akses->nama_controller == 'generalledger_coa' && $akses->can_create == 1){ echo 'checked'; } } ?> type="checkbox" value="1" name="coa_c"> Create</label></td>
                    <td><label class="label"> <input <?php foreach($role as $akses){ if($akses->nama_controller == 'generalledger_coa' && $akses->can_read == 1){ echo 'checked'; } } ?> type="checkbox" value="1" name="coa_r"> Read</label></td>
                    <td><label class="label"> <input <?php foreach($role as $akses){ if($akses->nama_controller == 'generalledger_coa' && $akses->can_update == 1){ echo 'checked'; } } ?> type="checkbox" value="1" name="coa_u"> Update</label></td>
                    <td><label class="label"> <input <?php foreach($role as $akses){ if($akses->nama_controller == 'generalledger_coa' && $akses->can_delete == 1){ echo 'checked'; } } ?> type="checkbox" value="1" name="coa_d"> Delete</label></td>
                </tr>
    
                <tr>
                    <td class="bg-light"  colspan="6"><strong>Cash Bank</strong> </td>
                </tr>
                <tr>
                    <td>Deposit/Payment</td>
                    <td><label class="label"> <input <?php foreach($role as $akses){ if($akses->nama_controller == 'cashbank' && $akses->can_index == 1){ echo 'checked'; } } ?> type="checkbox" value="1" name="dp_i"> Index</label></td>
                    <td><label class="label"> <input <?php foreach($role as $akses){ if($akses->nama_controller == 'cashbank' && $akses->can_create == 1){ echo 'checked'; } } ?> type="checkbox" value="1" name="dp_c"> Create</label></td>
                    <td><label class="label"> <input <?php foreach($role as $akses){ if($akses->nama_controller == 'cashbank' && $akses->can_read == 1){ echo 'checked'; } } ?> type="checkbox" value="1" name="dp_r"> Read</label></td>
                    <td><label class="label"> <input <?php foreach($role as $akses){ if($akses->nama_controller == 'cashbank' && $akses->can_update == 1){ echo 'checked'; } } ?> type="checkbox" value="1" name="dp_u"> Update</label></td>
                    <td><label class="label"> <input <?php foreach($role as $akses){ if($akses->nama_controller == 'cashbank' && $akses->can_delete == 1){ echo 'checked'; } } ?> type="checkbox" value="1" name="dp_d"> Delete</label></td>
                </tr>
                <tr>
                    <td>Bank Book</td>
                    <td><label class="label"> <input <?php foreach($role as $akses){ if($akses->nama_controller == 'cashbank_bukubank' && $akses->can_index == 1){ echo 'checked'; } } ?> type="checkbox" value="1" name="bb_i"> Index</label></td>
                    <td><label class="label"> <input <?php foreach($role as $akses){ if($akses->nama_controller == 'cashbank_bukubank' && $akses->can_create == 1){ echo 'checked'; } } ?> type="checkbox" value="1" name="bb_c"> Create</label></td>
                    <td><label class="label"> <input <?php foreach($role as $akses){ if($akses->nama_controller == 'cashbank_bukubank' && $akses->can_read == 1){ echo 'checked'; } } ?> type="checkbox" value="1" name="bb_r"> Read</label></td>
                    <td><label class="label"> <input <?php foreach($role as $akses){ if($akses->nama_controller == 'cashbank_bukubank' && $akses->can_update == 1){ echo 'checked'; } } ?> type="checkbox" value="1" name="bb_u"> Update</label></td>
                    <td><label class="label"> <input <?php foreach($role as $akses){ if($akses->nama_controller == 'cashbank_bukubank' && $akses->can_delete == 1){ echo 'checked'; } } ?> type="checkbox" value="1" name="bb_d"> Delete</label></td>
                </tr>
    
                <tr>
                    <td class="bg-light"  colspan="6"><strong>Purchasing</strong> </td>
                </tr>
                <tr>
                    <td>Purchase Request</td>
                    <td><label class="label"> <input <?php foreach($role as $akses){ if($akses->nama_controller == 'purchasing_pr' && $akses->can_index == 1){ echo 'checked'; } } ?> type="checkbox" value="1" name="pr_i"> Index</label></td>
                    <td><label class="label"> <input <?php foreach($role as $akses){ if($akses->nama_controller == 'purchasing_pr' && $akses->can_create == 1){ echo 'checked'; } } ?> type="checkbox" value="1" name="pr_c"> Create</label></td>
                    <td><label class="label"> <input <?php foreach($role as $akses){ if($akses->nama_controller == 'purchasing_pr' && $akses->can_read == 1){ echo 'checked'; } } ?> type="checkbox" value="1" name="pr_r"> Read</label></td>
                    <td><label class="label"> <input <?php foreach($role as $akses){ if($akses->nama_controller == 'purchasing_pr' && $akses->can_update == 1){ echo 'checked'; } } ?> type="checkbox" value="1" name="pr_u"> Update</label></td>
                    <td><label class="label"> <input <?php foreach($role as $akses){ if($akses->nama_controller == 'purchasing_pr' && $akses->can_delete == 1){ echo 'checked'; } } ?> type="checkbox" value="1" name="pr_d"> Delete</label></td>
                </tr>
                <tr>
                    <td>Purchase Order</td>
                    <td><label class="label"> <input <?php foreach($role as $akses){ if($akses->nama_controller == 'purchasing_po' && $akses->can_index == 1){ echo 'checked'; } } ?> type="checkbox" value="1" name="po_i"> Index</label></td>
                    <td><label class="label"> <input <?php foreach($role as $akses){ if($akses->nama_controller == 'purchasing_po' && $akses->can_create == 1){ echo 'checked'; } } ?> type="checkbox" value="1" name="po_c"> Create</label></td>
                    <td><label class="label"> <input <?php foreach($role as $akses){ if($akses->nama_controller == 'purchasing_po' && $akses->can_read == 1){ echo 'checked'; } } ?> type="checkbox" value="1" name="po_r"> Read</label></td>
                    <td><label class="label"> <input <?php foreach($role as $akses){ if($akses->nama_controller == 'purchasing_po' && $akses->can_update == 1){ echo 'checked'; } } ?> type="checkbox" value="1" name="po_u"> Update</label></td>
                    <td><label class="label"> <input <?php foreach($role as $akses){ if($akses->nama_controller == 'purchasing_po' && $akses->can_delete == 1){ echo 'checked'; } } ?> type="checkbox" value="1" name="po_d"> Delete</label></td>
                </tr>
                <tr>
                    <td>Receive Item</td>
                    <td><label class="label"> <input <?php foreach($role as $akses){ if($akses->nama_controller == 'purchasing_ri' && $akses->can_index == 1){ echo 'checked'; } } ?> type="checkbox" value="1" name="ri_i"> Index</label></td>
                    <td><label class="label"> <input <?php foreach($role as $akses){ if($akses->nama_controller == 'purchasing_ri' && $akses->can_create == 1){ echo 'checked'; } } ?> type="checkbox" value="1" name="ri_c"> Create</label></td>
                    <td><label class="label"> <input <?php foreach($role as $akses){ if($akses->nama_controller == 'purchasing_ri' && $akses->can_read == 1){ echo 'checked'; } } ?> type="checkbox" value="1" name="ri_r"> Read</label></td>
                    <td><label class="label"> <input <?php foreach($role as $akses){ if($akses->nama_controller == 'purchasing_ri' && $akses->can_update == 1){ echo 'checked'; } } ?> type="checkbox" value="1" name="ri_u"> Update</label></td>
                    <td><label class="label"> <input <?php foreach($role as $akses){ if($akses->nama_controller == 'purchasing_ri' && $akses->can_delete == 1){ echo 'checked'; } } ?> type="checkbox" value="1" name="ri_d"> Delete</label></td>
                </tr>
                <tr>
                    <td>Purchase Invoice</td>
                    <td><label class="label"> <input <?php foreach($role as $akses){ if($akses->nama_controller == 'purchasing_pi' && $akses->can_index == 1){ echo 'checked'; } } ?> type="checkbox" value="1" name="pi_i"> Index</label></td>
                    <td><label class="label"> <input <?php foreach($role as $akses){ if($akses->nama_controller == 'purchasing_pi' && $akses->can_create == 1){ echo 'checked'; } } ?> type="checkbox" value="1" name="pi_c"> Create</label></td>
                    <td><label class="label"> <input <?php foreach($role as $akses){ if($akses->nama_controller == 'purchasing_pi' && $akses->can_read == 1){ echo 'checked'; } } ?> type="checkbox" value="1" name="pi_r"> Read</label></td>
                    <td><label class="label"> <input <?php foreach($role as $akses){ if($akses->nama_controller == 'purchasing_pi' && $akses->can_update == 1){ echo 'checked'; } } ?> type="checkbox" value="1" name="pi_u"> Update</label></td>
                    <td><label class="label"> <input <?php foreach($role as $akses){ if($akses->nama_controller == 'purchasing_pi' && $akses->can_delete == 1){ echo 'checked'; } } ?> type="checkbox" value="1" name="pi_d"> Delete</label></td>
                </tr>
                <tr>
                    <td>Purchase Payment</td>
                    <td><label class="label"> <input <?php foreach($role as $akses){ if($akses->nama_controller == 'purchasing_pp' && $akses->can_index == 1){ echo 'checked'; } } ?> type="checkbox" value="1" name="pp_i"> Index</label></td>
                    <td><label class="label"> <input <?php foreach($role as $akses){ if($akses->nama_controller == 'purchasing_pp' && $akses->can_create == 1){ echo 'checked'; } } ?> type="checkbox" value="1" name="pp_c"> Create</label></td>
                    <td><label class="label"> <input <?php foreach($role as $akses){ if($akses->nama_controller == 'purchasing_pp' && $akses->can_read == 1){ echo 'checked'; } } ?> type="checkbox" value="1" name="pp_r"> Read</label></td>
                    <td><label class="label"> <input <?php foreach($role as $akses){ if($akses->nama_controller == 'purchasing_pp' && $akses->can_update == 1){ echo 'checked'; } } ?> type="checkbox" value="1" name="pp_u"> Update</label></td>
                    <td><label class="label"> <input <?php foreach($role as $akses){ if($akses->nama_controller == 'purchasing_pp' && $akses->can_delete == 1){ echo 'checked'; } } ?> type="checkbox" value="1" name="pp_d"> Delete</label></td>
                </tr>
    
                <tr>
                    <td class="bg-light"  colspan="6"><strong>Sales</strong> </td>
                </tr>
                <tr>
                    <td>Sales Order</td>
                    <td><label class="label"> <input <?php foreach($role as $akses){ if($akses->nama_controller == 'sales_so' && $akses->can_index == 1){ echo 'checked'; } } ?> type="checkbox" value="1" name="so_i"> Index</label></td>
                    <td><label class="label"> <input <?php foreach($role as $akses){ if($akses->nama_controller == 'sales_so' && $akses->can_create == 1){ echo 'checked'; } } ?> type="checkbox" value="1" name="so_c"> Create</label></td>
                    <td><label class="label"> <input <?php foreach($role as $akses){ if($akses->nama_controller == 'sales_so' && $akses->can_read == 1){ echo 'checked'; } } ?> type="checkbox" value="1" name="so_r"> Read</label></td>
                    <td><label class="label"> <input <?php foreach($role as $akses){ if($akses->nama_controller == 'sales_so' && $akses->can_update == 1){ echo 'checked'; } } ?> type="checkbox" value="1" name="so_u"> Update</label></td>
                    <td><label class="label"> <input <?php foreach($role as $akses){ if($akses->nama_controller == 'sales_so' && $akses->can_delete == 1){ echo 'checked'; } } ?> type="checkbox" value="1" name="so_d"> Delete</label></td>
                </tr>
                <tr>
                    <td>Delivery Order</td>
                    <td><label class="label"> <input <?php foreach($role as $akses){ if($akses->nama_controller == 'sales_do' && $akses->can_index == 1){ echo 'checked'; } } ?> type="checkbox" value="1" name="do_i"> Index</label></td>
                    <td><label class="label"> <input <?php foreach($role as $akses){ if($akses->nama_controller == 'sales_do' && $akses->can_create == 1){ echo 'checked'; } } ?> type="checkbox" value="1" name="do_c"> Create</label></td>
                    <td><label class="label"> <input <?php foreach($role as $akses){ if($akses->nama_controller == 'sales_do' && $akses->can_read == 1){ echo 'checked'; } } ?> type="checkbox" value="1" name="do_r"> Read</label></td>
                    <td><label class="label"> <input <?php foreach($role as $akses){ if($akses->nama_controller == 'sales_do' && $akses->can_update == 1){ echo 'checked'; } } ?> type="checkbox" value="1" name="do_u"> Update</label></td>
                    <td><label class="label"> <input <?php foreach($role as $akses){ if($akses->nama_controller == 'sales_do' && $akses->can_delete == 1){ echo 'checked'; } } ?> type="checkbox" value="1" name="do_d"> Delete</label></td>
                </tr>
                <tr>
                    <td>Sales Invoice</td>
                    <td><label class="label"> <input <?php foreach($role as $akses){ if($akses->nama_controller == 'sales_si' && $akses->can_index == 1){ echo 'checked'; } } ?> type="checkbox" value="1" name="si_i"> Index</label></td>
                    <td><label class="label"> <input <?php foreach($role as $akses){ if($akses->nama_controller == 'sales_si' && $akses->can_create == 1){ echo 'checked'; } } ?> type="checkbox" value="1" name="si_c"> Create</label></td>
                    <td><label class="label"> <input <?php foreach($role as $akses){ if($akses->nama_controller == 'sales_si' && $akses->can_read == 1){ echo 'checked'; } } ?> type="checkbox" value="1" name="si_r"> Read</label></td>
                    <td><label class="label"> <input <?php foreach($role as $akses){ if($akses->nama_controller == 'sales_si' && $akses->can_update == 1){ echo 'checked'; } } ?> type="checkbox" value="1" name="si_u"> Update</label></td>
                    <td><label class="label"> <input <?php foreach($role as $akses){ if($akses->nama_controller == 'sales_si' && $akses->can_delete == 1){ echo 'checked'; } } ?> type="checkbox" value="1" name="si_d"> Delete</label></td>
                </tr>
                <tr>
                    <td>Customer Receipt</td>
                    <td><label class="label"> <input <?php foreach($role as $akses){ if($akses->nama_controller == 'sales_cr' && $akses->can_index == 1){ echo 'checked'; } } ?> type="checkbox" value="1" name="cr_i"> Index</label></td>
                    <td><label class="label"> <input <?php foreach($role as $akses){ if($akses->nama_controller == 'sales_cr' && $akses->can_create == 1){ echo 'checked'; } } ?> type="checkbox" value="1" name="cr_c"> Create</label></td>
                    <td><label class="label"> <input <?php foreach($role as $akses){ if($akses->nama_controller == 'sales_cr' && $akses->can_read == 1){ echo 'checked'; } } ?> type="checkbox" value="1" name="cr_r"> Read</label></td>
                    <td><label class="label"> <input <?php foreach($role as $akses){ if($akses->nama_controller == 'sales_cr' && $akses->can_update == 1){ echo 'checked'; } } ?> type="checkbox" value="1" name="cr_u"> Update</label></td>
                    <td><label class="label"> <input <?php foreach($role as $akses){ if($akses->nama_controller == 'sales_cr' && $akses->can_delete == 1){ echo 'checked'; } } ?> type="checkbox" value="1" name="cr_d"> Delete</label></td>
                </tr>
    
                <tr>
                    <td class="bg-light"  colspan="6"><strong>Inventory</strong>  </td>
                </tr>
                <tr>
                    <td>List Inventory</td>
                    <td><label class="label"> <input <?php foreach($role as $akses){ if($akses->nama_controller == 'inventory_li' && $akses->can_index == 1){ echo 'checked'; } } ?> type="checkbox" value="1" name="li_i"> Index</label></td>
                    <td><label class="label"> <input <?php foreach($role as $akses){ if($akses->nama_controller == 'inventory_li' && $akses->can_create == 1){ echo 'checked'; } } ?> type="checkbox" value="1" name="li_c"> Create</label></td>
                    <td><label class="label"> <input <?php foreach($role as $akses){ if($akses->nama_controller == 'inventory_li' && $akses->can_read == 1){ echo 'checked'; } } ?> type="checkbox" value="1" name="li_r"> Read</label></td>
                    <td><label class="label"> <input <?php foreach($role as $akses){ if($akses->nama_controller == 'inventory_li' && $akses->can_update == 1){ echo 'checked'; } } ?> type="checkbox" value="1" name="li_u"> Update</label></td>
                    <td><label class="label"> <input <?php foreach($role as $akses){ if($akses->nama_controller == 'inventory_li' && $akses->can_delete == 1){ echo 'checked'; } } ?> type="checkbox" value="1" name="li_d"> Delete</label></td>
                </tr>
                <tr>
                    <td>Stock Adjusment</td>
                    <td><label class="label"> <input <?php foreach($role as $akses){ if($akses->nama_controller == 'inventory_st' && $akses->can_index == 1){ echo 'checked'; } } ?> type="checkbox" value="1" name="st_i"> Index</label></td>
                    <td><label class="label"> <input <?php foreach($role as $akses){ if($akses->nama_controller == 'inventory_st' && $akses->can_create == 1){ echo 'checked'; } } ?> type="checkbox" value="1" name="st_c"> Create</label></td>
                    <td><label class="label"> <input <?php foreach($role as $akses){ if($akses->nama_controller == 'inventory_st' && $akses->can_read == 1){ echo 'checked'; } } ?> type="checkbox" value="1" name="st_r"> Read</label></td>
                    <td><label class="label"> <input <?php foreach($role as $akses){ if($akses->nama_controller == 'inventory_st' && $akses->can_update == 1){ echo 'checked'; } } ?> type="checkbox" value="1" name="st_u"> Update</label></td>
                    <td><label class="label"> <input <?php foreach($role as $akses){ if($akses->nama_controller == 'inventory_st' && $akses->can_delete == 1){ echo 'checked'; } } ?> type="checkbox" value="1" name="st_d"> Delete</label></td>
                </tr>
                <tr>
                    <td>Mutation</td>
                    <td><label class="label"> <input <?php foreach($role as $akses){ if($akses->nama_controller == 'inventory_mt' && $akses->can_index == 1){ echo 'checked'; } } ?> type="checkbox" value="1" name="mt_i"> Index</label></td>
                    <td><label class="label"> <input <?php foreach($role as $akses){ if($akses->nama_controller == 'inventory_mt' && $akses->can_create == 1){ echo 'checked'; } } ?> type="checkbox" value="1" name="mt_c"> Create</label></td>
                    <td><label class="label"> <input <?php foreach($role as $akses){ if($akses->nama_controller == 'inventory_mt' && $akses->can_read == 1){ echo 'checked'; } } ?> type="checkbox" value="1" name="mt_r"> Read</label></td>
                    <td><label class="label"> <input <?php foreach($role as $akses){ if($akses->nama_controller == 'inventory_mt' && $akses->can_update == 1){ echo 'checked'; } } ?> type="checkbox" value="1" name="mt_u"> Update</label></td>
                    <td><label class="label"> <input <?php foreach($role as $akses){ if($akses->nama_controller == 'inventory_mt' && $akses->can_delete == 1){ echo 'checked'; } } ?> type="checkbox" value="1" name="mt_d"> Delete</label></td>
                </tr>
    
    
                <tr>
                    <td class="bg-light"  colspan="6"><strong>Category</strong> </td>
                </tr>
                <tr>
                    <td>Items Type</td>
                    <td><label class="label"> <input <?php foreach($role as $akses){ if($akses->nama_controller == 'category_it' && $akses->can_index == 1){ echo 'checked'; } } ?> type="checkbox" value="1" name="it_i"> Index</label></td>
                    <td><label class="label"> <input <?php foreach($role as $akses){ if($akses->nama_controller == 'category_it' && $akses->can_create == 1){ echo 'checked'; } } ?> type="checkbox" value="1" name="it_c"> Create</label></td>
                    <td><label class="label"> <input <?php foreach($role as $akses){ if($akses->nama_controller == 'category_it' && $akses->can_read == 1){ echo 'checked'; } } ?> type="checkbox" value="1" name="it_r"> Read</label></td>
                    <td><label class="label"> <input <?php foreach($role as $akses){ if($akses->nama_controller == 'category_it' && $akses->can_update == 1){ echo 'checked'; } } ?> type="checkbox" value="1" name="it_u"> Update</label></td>
                    <td><label class="label"> <input <?php foreach($role as $akses){ if($akses->nama_controller == 'category_it' && $akses->can_delete == 1){ echo 'checked'; } } ?> type="checkbox" value="1" name="it_d"> Delete</label></td>
                </tr>
                <tr>
                    <td>Customer Type</td>
                    <td><label class="label"> <input <?php foreach($role as $akses){ if($akses->nama_controller == 'category_cs' && $akses->can_index == 1){ echo 'checked'; } } ?> type="checkbox" value="1" name="cs_i"> Index</label></td>
                    <td><label class="label"> <input <?php foreach($role as $akses){ if($akses->nama_controller == 'category_cs' && $akses->can_create == 1){ echo 'checked'; } } ?> type="checkbox" value="1" name="cs_c"> Create</label></td>
                    <td><label class="label"> <input <?php foreach($role as $akses){ if($akses->nama_controller == 'category_cs' && $akses->can_read == 1){ echo 'checked'; } } ?> type="checkbox" value="1" name="cs_r"> Read</label></td>
                    <td><label class="label"> <input <?php foreach($role as $akses){ if($akses->nama_controller == 'category_cs' && $akses->can_update == 1){ echo 'checked'; } } ?> type="checkbox" value="1" name="cs_u"> Update</label></td>
                    <td><label class="label"> <input <?php foreach($role as $akses){ if($akses->nama_controller == 'category_cs' && $akses->can_delete == 1){ echo 'checked'; } } ?> type="checkbox" value="1" name="cs_d"> Delete</label></td>
                </tr>
                <tr>
                    <td>Supplier Type</td>
                    <td><label class="label"> <input <?php foreach($role as $akses){ if($akses->nama_controller == 'category_su' && $akses->can_index == 1){ echo 'checked'; } } ?> type="checkbox" value="1" name="su_i"> Index</label></td>
                    <td><label class="label"> <input <?php foreach($role as $akses){ if($akses->nama_controller == 'category_su' && $akses->can_create == 1){ echo 'checked'; } } ?> type="checkbox" value="1" name="su_c"> Create</label></td>
                    <td><label class="label"> <input <?php foreach($role as $akses){ if($akses->nama_controller == 'category_su' && $akses->can_read == 1){ echo 'checked'; } } ?> type="checkbox" value="1" name="su_r"> Read</label></td>
                    <td><label class="label"> <input <?php foreach($role as $akses){ if($akses->nama_controller == 'category_su' && $akses->can_update == 1){ echo 'checked'; } } ?> type="checkbox" value="1" name="su_u"> Update</label></td>
                    <td><label class="label"> <input <?php foreach($role as $akses){ if($akses->nama_controller == 'category_su' && $akses->can_delete == 1){ echo 'checked'; } } ?> type="checkbox" value="1" name="su_d"> Delete</label></td>
                </tr>
    
                
                <tr>
                    <td class="bg-light"  colspan="6"><strong>HR & GA</strong> </td>
                </tr>
                <tr>
                    <td>Employee</td>
                    <td><label class="label"> <input <?php foreach($role as $akses){ if($akses->nama_controller == 'hrga_employee' && $akses->can_index == 1){ echo 'checked'; } } ?> type="checkbox" value="1" name="employee_i"> Index</label></td>
                    <td><label class="label"> <input <?php foreach($role as $akses){ if($akses->nama_controller == 'hrga_employee' && $akses->can_create == 1){ echo 'checked'; } } ?> type="checkbox" value="1" name="employee_c"> Create</label></td>
                    <td><label class="label"> <input <?php foreach($role as $akses){ if($akses->nama_controller == 'hrga_employee' && $akses->can_read == 1){ echo 'checked'; } } ?> type="checkbox" value="1" name="employee_r"> Read</label></td>
                    <td><label class="label"> <input <?php foreach($role as $akses){ if($akses->nama_controller == 'hrga_employee' && $akses->can_update == 1){ echo 'checked'; } } ?> type="checkbox" value="1" name="employee_u"> Update</label></td>
                    <td><label class="label"> <input <?php foreach($role as $akses){ if($akses->nama_controller == 'hrga_employee' && $akses->can_delete == 1){ echo 'checked'; } } ?> type="checkbox" value="1" name="employee_d"> Delete</label></td>
                </tr>
                <tr>
                    <td>Asset</td>
                    <td><label class="label"> <input <?php foreach($role as $akses){ if($akses->nama_controller == 'hrga_asset' && $akses->can_index == 1){ echo 'checked'; } } ?> type="checkbox" value="1" name="asset_i"> Index</label></td>
                    <td><label class="label"> <input <?php foreach($role as $akses){ if($akses->nama_controller == 'hrga_asset' && $akses->can_create == 1){ echo 'checked'; } } ?> type="checkbox" value="1" name="asset_c"> Create</label></td>
                    <td><label class="label"> <input <?php foreach($role as $akses){ if($akses->nama_controller == 'hrga_asset' && $akses->can_read == 1){ echo 'checked'; } } ?> type="checkbox" value="1" name="asset_r"> Read</label></td>
                    <td><label class="label"> <input <?php foreach($role as $akses){ if($akses->nama_controller == 'hrga_asset' && $akses->can_update == 1){ echo 'checked'; } } ?> type="checkbox" value="1" name="asset_u"> Update</label></td>
                    <td><label class="label"> <input <?php foreach($role as $akses){ if($akses->nama_controller == 'hrga_asset' && $akses->can_delete == 1){ echo 'checked'; } } ?> type="checkbox" value="1" name="asset_d"> Delete</label></td>
                </tr>
                <tr>
                    <td>Category Asset</td>
                    <td><label class="label"> <input <?php foreach($role as $akses){ if($akses->nama_controller == 'hrga_category_asset' && $akses->can_index == 1){ echo 'checked'; } } ?> type="checkbox" value="1" name="category_asset_i"> Index</label></td>
                    <td><label class="label"> <input <?php foreach($role as $akses){ if($akses->nama_controller == 'hrga_category_asset' && $akses->can_create == 1){ echo 'checked'; } } ?> type="checkbox" value="1" name="category_asset_c"> Create</label></td>
                    <td><label class="label"> <input <?php foreach($role as $akses){ if($akses->nama_controller == 'hrga_category_asset' && $akses->can_read == 1){ echo 'checked'; } } ?> type="checkbox" value="1" name="category_asset_r"> Read</label></td>
                    <td><label class="label"> <input <?php foreach($role as $akses){ if($akses->nama_controller == 'hrga_category_asset' && $akses->can_update == 1){ echo 'checked'; } } ?> type="checkbox" value="1" name="category_asset_u"> Update</label></td>
                    <td><label class="label"> <input <?php foreach($role as $akses){ if($akses->nama_controller == 'hrga_category_asset' && $akses->can_delete == 1){ echo 'checked'; } } ?> type="checkbox" value="1" name="category_asset_d"> Delete</label></td>
                </tr>
                <!-- <tr>
                    <td>Absent</td>
                    <td><label class="label"> <input type="checkbox" value="1" name="ab_i"> Index</label></td>
                    <td><label class="label"> <input type="checkbox" value="1" name="ab_c"> Create</label></td>
                    <td><label class="label"> <input type="checkbox" value="1" name="ab_r"> Read</label></td>
                    <td><label class="label"> <input type="checkbox" value="1" name="ab_u"> Update</label></td>
                    <td><label class="label"> <input type="checkbox" value="1" name="ab_d"> Delete</label></td>
                </tr> -->
    
                <tr>
                    <td class="bg-light"  colspan="6"><strong>Master</strong> </td>
                </tr>
                <tr>
                    <td>Items</td>
                    <td><label class="label"> <input <?php foreach($role as $akses){ if($akses->nama_controller == 'master_it' && $akses->can_index == 1){ echo 'checked'; } } ?> type="checkbox" value="1" name="item_i"> Index</label></td>
                    <td><label class="label"> <input <?php foreach($role as $akses){ if($akses->nama_controller == 'master_it' && $akses->can_create== 1){ echo 'checked'; } } ?> type="checkbox" value="1" name="item_c"> Create</label></td>
                    <td><label class="label"> <input <?php foreach($role as $akses){ if($akses->nama_controller == 'master_it' && $akses->can_read == 1){ echo 'checked'; } } ?> type="checkbox" value="1" name="item_r"> Read</label></td>
                    <td><label class="label"> <input <?php foreach($role as $akses){ if($akses->nama_controller == 'master_it' && $akses->can_update == 1){ echo 'checked'; } } ?> type="checkbox" value="1" name="item_u"> Update</label></td>
                    <td><label class="label"> <input <?php foreach($role as $akses){ if($akses->nama_controller == 'master_it' && $akses->can_delete == 1){ echo 'checked'; } } ?> type="checkbox" value="1" name="item_d"> Delete</label></td>
                </tr>
                
                <tr>
                    <td>Packet</td>
                    <td><label class="label"> <input <?php foreach($role as $akses){ if($akses->nama_controller == 'master_pck' && $akses->can_index == 1){ echo 'checked'; } } ?> type="checkbox" value="1" name="packet_i"> Index</label></td>
                    <td><label class="label"> <input <?php foreach($role as $akses){ if($akses->nama_controller == 'master_pck' && $akses->can_create== 1){ echo 'checked'; } } ?> type="checkbox" value="1" name="packet_c"> Create</label></td>
                    <td><label class="label"> <input <?php foreach($role as $akses){ if($akses->nama_controller == 'master_pck' && $akses->can_read == 1){ echo 'checked'; } } ?> type="checkbox" value="1" name="packet_r"> Read</label></td>
                    <td><label class="label"> <input <?php foreach($role as $akses){ if($akses->nama_controller == 'master_pck' && $akses->can_update == 1){ echo 'checked'; } } ?> type="checkbox" value="1" name="packet_u"> Update</label></td>
                    <td><label class="label"> <input <?php foreach($role as $akses){ if($akses->nama_controller == 'master_pck' && $akses->can_delete == 1){ echo 'checked'; } } ?> type="checkbox" value="1" name="packet_d"> Delete</label></td>
                </tr>
                <tr>
                    <td>Warehouses</td>
                    <td><label class="label"> <input <?php foreach($role as $akses){ if($akses->nama_controller == 'master_wr' && $akses->can_index == 1){ echo 'checked'; } } ?> type="checkbox" value="1" name="wr_i"> Index</label></td>
                    <td><label class="label"> <input <?php foreach($role as $akses){ if($akses->nama_controller == 'master_wr' && $akses->can_create == 1){ echo 'checked'; } } ?> type="checkbox" value="1" name="wr_c"> Create</label></td>
                    <td><label class="label"> <input <?php foreach($role as $akses){ if($akses->nama_controller == 'master_wr' && $akses->can_read == 1){ echo 'checked'; } } ?> type="checkbox" value="1" name="wr_r"> Read</label></td>
                    <td><label class="label"> <input <?php foreach($role as $akses){ if($akses->nama_controller == 'master_wr' && $akses->can_update == 1){ echo 'checked'; } } ?> type="checkbox" value="1" name="wr_u"> Update</label></td>
                    <td><label class="label"> <input <?php foreach($role as $akses){ if($akses->nama_controller == 'master_wr' && $akses->can_delete == 1){ echo 'checked'; } } ?> type="checkbox" value="1" name="wr_d"> Delete</label></td>
                </tr>
                <tr>
                    <td>Customers</td>
                    <td><label class="label"> <input <?php foreach($role as $akses){ if($akses->nama_controller == 'master_cs' && $akses->can_index == 1){ echo 'checked'; } } ?> type="checkbox" value="1" name="cust_i"> Index</label></td>
                    <td><label class="label"> <input <?php foreach($role as $akses){ if($akses->nama_controller == 'master_cs' && $akses->can_create == 1){ echo 'checked'; } } ?> type="checkbox" value="1" name="cust_c"> Create</label></td>
                    <td><label class="label"> <input <?php foreach($role as $akses){ if($akses->nama_controller == 'master_cs' && $akses->can_read == 1){ echo 'checked'; } } ?> type="checkbox" value="1" name="cust_r"> Read</label></td>
                    <td><label class="label"> <input <?php foreach($role as $akses){ if($akses->nama_controller == 'master_cs' && $akses->can_update == 1){ echo 'checked'; } } ?> type="checkbox" value="1" name="cust_u"> Update</label></td>
                    <td><label class="label"> <input <?php foreach($role as $akses){ if($akses->nama_controller == 'master_cs' && $akses->can_delete == 1){ echo 'checked'; } } ?> type="checkbox" value="1" name="cust_d"> Delete</label></td>
                </tr>
                <tr>
                    <td>Suppliers</td>
                    <td><label class="label"> <input <?php foreach($role as $akses){ if($akses->nama_controller == 'master_sp' && $akses->can_index == 1){ echo 'checked'; } } ?> type="checkbox" value="1" name="sp_i"> Index</label></td>
                    <td><label class="label"> <input <?php foreach($role as $akses){ if($akses->nama_controller == 'master_sp' && $akses->can_create == 1){ echo 'checked'; } } ?> type="checkbox" value="1" name="sp_c"> Create</label></td>
                    <td><label class="label"> <input <?php foreach($role as $akses){ if($akses->nama_controller == 'master_sp' && $akses->can_read == 1){ echo 'checked'; } } ?> type="checkbox" value="1" name="sp_r"> Read</label></td>
                    <td><label class="label"> <input <?php foreach($role as $akses){ if($akses->nama_controller == 'master_sp' && $akses->can_update == 1){ echo 'checked'; } } ?> type="checkbox" value="1" name="sp_u"> Update</label></td>
                    <td><label class="label"> <input <?php foreach($role as $akses){ if($akses->nama_controller == 'master_sp' && $akses->can_delete == 1){ echo 'checked'; } } ?> type="checkbox" value="1" name="sp_d"> Delete</label></td>
                </tr>
                <tr>
                    <td>Sales</td>
                    <td><label class="label"> <input <?php foreach($role as $akses){ if($akses->nama_controller == 'master_sl' && $akses->can_index == 1){ echo 'checked'; } } ?> type="checkbox" value="1" name="sls_i"> Index</label></td>
                    <td><label class="label"> <input <?php foreach($role as $akses){ if($akses->nama_controller == 'master_sl' && $akses->can_create == 1){ echo 'checked'; } } ?> type="checkbox" value="1" name="sls_c"> Create</label></td>
                    <td><label class="label"> <input <?php foreach($role as $akses){ if($akses->nama_controller == 'master_sl' && $akses->can_read == 1){ echo 'checked'; } } ?> type="checkbox" value="1" name="sls_r"> Read</label></td>
                    <td><label class="label"> <input <?php foreach($role as $akses){ if($akses->nama_controller == 'master_sl' && $akses->can_update == 1){ echo 'checked'; } } ?> type="checkbox" value="1" name="sls_u"> Update</label></td>
                    <td><label class="label"> <input <?php foreach($role as $akses){ if($akses->nama_controller == 'master_sl' && $akses->can_delete == 1){ echo 'checked'; } } ?> type="checkbox" value="1" name="sls_d"> Delete</label></td>
                </tr>
                <tr>
                    <td class="bg-light"  colspan="6"><strong>Role Akses</strong> </td>
                </tr>
                <tr>
                    <td>Role Management</td>
                    <td><label class="label"> <input <?php foreach($role as $akses){ if($akses->nama_controller == 'role_akses' && $akses->can_index == 1){ echo 'checked'; } } ?> type="checkbox" value="1" name="rm_i"> Index</label></td>
                    <td><label class="label"> <input <?php foreach($role as $akses){ if($akses->nama_controller == 'role_akses' && $akses->can_create == 1){ echo 'checked'; } } ?> type="checkbox" value="1" name="rm_c"> Create</label></td>
                    <td><label class="label"> <input <?php foreach($role as $akses){ if($akses->nama_controller == 'role_akses' && $akses->can_read == 1){ echo 'checked'; } } ?> type="checkbox" value="1" name="rm_r"> Read</label></td>
                    <td><label class="label"> <input <?php foreach($role as $akses){ if($akses->nama_controller == 'role_akses' && $akses->can_update == 1){ echo 'checked'; } } ?> type="checkbox" value="1" name="rm_u"> Update</label></td>
                    <td><label class="label"> <input <?php foreach($role as $akses){ if($akses->nama_controller == 'role_akses' && $akses->can_delete == 1){ echo 'checked'; } } ?> type="checkbox" value="1" name="rm_d"> Delete</label></td>
                </tr>
                <tr>
                    <td class="bg-light"  colspan="6"><strong>Action</strong> </td>
                </tr>
                <tr>
                    <td>Button Acc Checked SO</td>
                    <td><label class="label"> <input <?php foreach($role as $akses){ if($akses->nama_controller == 'btn_acc_checked' && $akses->can_index == 1){ echo 'checked'; } } ?> type="checkbox" value="1" name="btn_check_so"> All Access</label></td>
                </tr>
            </table>
        </div>
        
    </div>
    <div class="card-footer">
    <button type="submit" class="btn btn-primary">Simpan</button>
    </form>
    </div>
</div>
@endsection