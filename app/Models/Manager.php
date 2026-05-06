<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Manager extends Authenticatable
{
    use Notifiable;

    protected $table = 'managers';

    protected $fillable = [
        'username',
        'email',
        'password',
        'full_name',
        'phone',
        'manager_type',
        'status',
        'created_by'
    ];

    protected $hidden = [
        'password',
        'remember_token'
    ];

    /**
     * الجمعيات التي سجّلها هذا المدير كمنشئ (organizations.created_by).
     */
    public function createdOrganizations(): HasMany
    {
        return $this->hasMany(Organization::class, 'created_by');
    }

    /**
     * جمعية واحدة مرتبطة بالمدير عند إنشاء الفعاليات/التقارير (آخر جمعية أنشأها).
     */
    public function organization(): HasOne
    {
        return $this->hasOne(Organization::class, 'created_by')->latestOfMany();
    }
}
