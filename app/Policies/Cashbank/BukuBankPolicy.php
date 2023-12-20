<?php

namespace App\Policies\Cashbank;

use App\Models\BukuBankRinci;
use App\Models\User;
use App\Models\Roleakses;

use Illuminate\Auth\Access\HandlesAuthorization;

class BukuBankPolicy
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
            'nama_controller' => 'cashbank_bukubank',
            'can_index' => 1,
            'user_id' => $user->id,
        ])->first() != null) return true;
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\BukuBankRinci  $bukuBankRinci
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, BukuBankRinci $bukuBankRinci)
    {
        //
        if(Roleakses::where([
            'nama_controller' => 'cashbank_bukubank',
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
            'nama_controller' => 'cashbank_bukubank',
            'can_create' => 1,
            'user_id' => $user->id,
        ])->first() != null) return true;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\BukuBankRinci  $bukuBankRinci
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, BukuBankRinci $bukuBankRinci)
    {
        //
        if(Roleakses::where([
            'nama_controller' => 'cashbank_bukubank',
            'can_update' => 1,
            'user_id' => $user->id,
        ])->first() != null) return true;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\BukuBankRinci  $bukuBankRinci
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, BukuBankRinci $bukuBankRinci)
    {
        //
        if(Roleakses::where([
            'nama_controller' => 'cashbank_bukubank',
            'can_delete' => 1,
            'user_id' => $user->id,
        ])->first() != null) return true;
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\BukuBankRinci  $bukuBankRinci
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, BukuBankRinci $bukuBankRinci)
    {
        //
        if(Roleakses::where([
            'nama_controller' => 'cashbank_bukubank',
            'can_delete' => 1,
            'user_id' => $user->id,
        ])->first() != null) return true;
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\BukuBankRinci  $bukuBankRinci
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, BukuBankRinci $bukuBankRinci)
    {
        //
        if(Roleakses::where([
            'nama_controller' => 'cashbank_bukubank',
            'can_delete' => 1,
            'user_id' => $user->id,
        ])->first() != null) return true;
    }
}