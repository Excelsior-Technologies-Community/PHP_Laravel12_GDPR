<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

//  GDPR Imports
use Soved\Laravel\Gdpr\Portable;
use Soved\Laravel\Gdpr\Retentionable;
use Soved\Laravel\Gdpr\EncryptsAttributes;
use Soved\Laravel\Gdpr\Contracts\Portable as PortableContract;

class User extends Authenticatable implements PortableContract
{
    use HasFactory, Notifiable;

    //  Add GDPR Traits
    use Portable, Retentionable, EncryptsAttributes;

    protected $fillable = [
        'name',
        'email',
        'password',
        'ssnumber' // optional
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    //  GDPR: Hide sensitive data
    protected $gdprHidden = ['password'];

    //  GDPR: Encrypt fields
    protected $encrypted = ['ssnumber'];

    //  GDPR: Include relations (optional)
    protected $gdprWith = [];

    /**
     * Customize downloadable data
     */
    public function toPortableArray()
    {
        return [
            'name' => $this->name,
            'email' => $this->email,
            'ssnumber' => $this->ssnumber, // ADD
            'created_at' => $this->created_at,
        ];
    }

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}