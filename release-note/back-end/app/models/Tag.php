<?php
    namespace App\Models;

    use Illuminate\Database\Eloquent\Model;

    class Tag extends Model
    {
        public $timestamps = false;
        protected $hidden = ['pivot'];
        /**
         * The table associated with the model.
         *
         * @var string
         */
        protected $table = 't_tag';

        /**
         * The primary key associated with the table.
         *
         * @var integer
         */
        protected $primaryKey = 'id';

        /**
         * The primary key associated with the table.
         *
         * @var string
         */
        protected $name = 'name';


        public function features()
        {
            return $this->belongsToMany('App\Models\Feature', 'tj_feature_tag');
        }
  
    }
?>