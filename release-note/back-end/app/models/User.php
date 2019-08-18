<?php
    namespace App\Models;

    use Illuminate\Database\Eloquent\Model;

    class User extends Model
    {
        public $timestamps = false;
        /**
         * The table associated with the model.
         *
         * @var string
         */
        protected $table = 't_user';

        /**
         * The primary key associated with the table.
         *
         * @var integer
         */
        protected $primaryKey = 'id';

        /**
         * The primary key associated with the table.
         * @var string
         */
        protected $fillable = ['user', "password"];
    }
?>