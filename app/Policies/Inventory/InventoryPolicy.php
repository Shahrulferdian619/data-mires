<?php

namespace App\Policies\Inventory;

use App\Models\TransaksiBarang;
use App\Models\User;
use App\Models\Roleakses;
use Illuminate\Auth\Access\HandlesAuthorization;

class InventoryPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewAny(User $user)
    {
        if(Roleakses::where([
            'nama_controller' => 'inventory_li',
            'can_index' => 1,
            'user_id' => $user->id
        ])->first() != null) return true;
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\TransaksiBarang  $transaksiBarang
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user)
    {
        if(Roleakses::where([
            'nama_controller' => 'inventory_li',
            'can_read' => 1,
            'user_id' => $user->id
        ])->first() != null) return true;
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        if(Roleakses::where([
            'nama_controller' => 'inventory_li',
            'can_create' => 1,
            'user_id' => $user->id
        ])->first() != null) return true;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\TransaksiBarang  $transaksiBarang
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user)
    {
        if(Roleakses::where([
            'nama_controller' => 'inventory_li',
            'can_update' => 1,
            'user_id' => $user->id
        ])->first() != null) return true;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\TransaksiBarang  $transaksiBarang
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user)
    {
        if(Roleakses::where([
            'nama_controller' => 'inventory_li',
            'can_delete' => 1,
            'user_id' => $user->id
        ])->first() != null) return true;
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\TransaksiBarang  $transaksiBarang
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user)
    {
        if(Roleakses::where([
            'nama_controller' => 'inventory_li',
            'can_delete' => 1,
            'user_id' => $user->id
        ])->first() != null) return true;
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\TransaksiBarang  $transaksiBarang
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user)
    {
        //
    }
}
