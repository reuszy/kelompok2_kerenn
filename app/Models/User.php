<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    // Default
    // /** @use HasFactory<\Database\Factories\UserFactory> */
    // use HasFactory, Notifiable;

    // /**
    //  * The attributes that are mass assignable.
    //  *
    //  * @var list<string>
    //  */
    // protected $fillable = [
    //     'name',
    //     'email',
    //     'password',
    // ];

    // /**
    //  * The attributes that should be hidden for serialization.
    //  *
    //  * @var list<string>
    //  */
    // protected $hidden = [
    //     'password',
    //     'remember_token',
    // ];

    // /**
    //  * Get the attributes that should be cast.
    //  *
    //  * @return array<string, string>
    //  */
    // protected function casts(): array
    // {
    //     return [
    //         'email_verified_at' => 'datetime',
    //         'password' => 'hashed',
    //     ];
    // }

    // Custom
    use HasFactory, Notifiable;

    public function getJWTIdentifier()
    {
        return $this->getKey(); // biasanya primary key user (id)
    }

    /**
     * Return a key value array, containing any custom claims to be added to JWT.
     */
    public function getJWTCustomClaims()
    {
        return [
            'role' => $this->role,
        ];
    }

    protected $guarded = [
        'id'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    // public function officers()
    // {
    //     return $this->belongsTo(Officer::class, 'officer_id');
    // }

    // public function familyParents()
    // {
    //     return $this->belongsTo(FamilyParent::class, 'parent_id');
    // }

    // public static function adminCount()
    // {
    //     return self::where('role', 'admin')->count();  // Filter berdasarkan 'role' = 'admin'
    // }

    // public static function midwifeCount()
    // {
    //     return self::where('role', 'midwife')->count();  // Filter berdasarkan 'role' = 'midwife'
    // }

    // public static function officerCount()
    // {
    //     return self::where('role', 'officer')->count();  // Filter berdasarkan 'role' = 'officer'
    // }

    // public static function familyParentCount()
    // {
    //     return self::where('role', 'peminjam')->count();  // Filter berdasarkan 'role' = 'peminjam'
    // }
}
