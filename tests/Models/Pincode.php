<?php
    
    namespace YS\Relations\Tests\Models;
    
    use Illuminate\Database\Eloquent\Model;
    
    class Pincode extends Model
    {
        /**
         * The name of table model is associated with.
         *
         * @var string
         */
        protected $table='pincodes';
        
        /**
         * The attributes that are mass assignable.
         *
         * @var array
         */
        protected $fillable = [
            'pincode','state_id'
        ];
        
    }
