<?php


namespace App\Repositories\Histories;


use App\Repositories\Users\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use function companyID;

class UserHistory extends Model
{

    protected $table = "user_history";

    protected $fillable = [
        'user_id',
        'user_full_name',
        'type',
        'description',
        'model'
    ];

    CONST SESSION = 'session';
    CONST UPDATED = 'actualizar';
    CONST STORE = 'guardar';
    CONST DELETE = 'eliminar';
    CONST DISABLE = 'desactivar';
    CONST ENABLE = 'activar';
    CONST NOTIFY = 'notificar';
    CONST EXPORT = 'exportar';
    CONST IMPORT = 'importar';
    CONST RESET = 'resetear';
    CONST REVERSE = 'revertir';
    CONST APPROVED = 'aprobar';
    CONST DUPLICATE = 'duplicar';



    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public static function boot()
    {
        parent::boot();

        static::saving(function ($record) {
            if (Auth::check())
                $record->company_id  = companyID();
        });
    }
}
