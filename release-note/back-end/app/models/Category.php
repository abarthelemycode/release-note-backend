<?php
    namespace App\Models;

    use Illuminate\Database\Eloquent\Model;

    class Category extends Model
    {
        public $timestamps = false;

        /**
         * The table associated with the model.
         *
         * @var string
         */
        protected $table = 't_category';

        /**
         * The primary key associated with the table.
         *
         * @var integer
         */
        protected $primaryKey = 'id';

        /**
         * 
         * @var string
         */
        protected $fillable = ['name'];
       
    }
?>