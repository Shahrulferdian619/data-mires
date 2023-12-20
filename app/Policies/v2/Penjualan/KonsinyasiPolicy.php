<?php

namespace App\Policies\v2\Penjualan;

use App\Models\Konsinyasi;
use App\Models\Roleakses;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class KonsinyasiPolicy
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
        //
        if(Roleakses::where([
            'nama_controller' => 'konsinyasi',
            'can_index' => 1,
            'user_id' => $user->id,
        ])->first() != null) return true;
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Konsinyasi  $konsinyasi
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user)
    {
        //
        if(Roleakses::where([
            'nama_controller' => 'konsinyasi',
            'can_read' => 1,
            'user_id' => $user->id,
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
        //
        if(Roleakses::where([
            'nama_controller' => 'konsinyasi',
            'can_create' => 1,
            'user_id' => $user->id,
        ])->first() != null) return true;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Konsinyasi  $konsinyasi
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user)
    {
        //
        if(Roleakses::where([
            'nama_controller' => 'konsinyasi',
            'can_update' => 1,
            'user_id' => $user->id,
        ])->first() != null) return true;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Konsinyasi  $konsinyasi
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user)
    {
        //
        if(Roleakses::where([
            'nama_controller' => 'konsinyasi',
            'can_delete' => 1,
            'user_id' => $user->id,
        ])->first() != null) return true;
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Konsinyasi  $konsinyasi
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Konsinyasi  $konsinyasi
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user)
    {
        //
    }
}