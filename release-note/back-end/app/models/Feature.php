<?php
    namespace App\Models;

    use Illuminate\Database\Eloquent\Model;

    class Feature extends Model
    {
        public $timestamps = false;
        protected $hidden = ['category_id', 'pivot'];

        /**
         * The table associated with the model.
         *
         * @var string
         */
        protected $table = 't_feature';

        /**
         * The primary key associated with the table.
         *
         * @var integer
         */
        protected $primaryKey = 'id';

        protected $fillable = ['name', "text", "link", "is_image", "version", "order"];
        
        
        public function tags()
        {
            return $this->belongsToMany('App\Models\Tag', 'tj_feature_tag');
        }
        
        /**
         * Get the category record associated with the feature.
         */
        public function category()
        {
            return $this->belongsTo('App\Models\Category');
        }
    }
?>