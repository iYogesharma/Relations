<?php
    
    namespace YS\Relations\Tests\Models;
    
    use Illuminate\Database\Eloquent\Model;
    
    class State extends Model
    {
        /**
         * The name of table model is associated with.
         *
         * @var string
         */
        protected $table='states';
        
        /**
         * The attributes that are mass assignable.
         *
         * @var array
         */
        protected $fillable = [
            'name',
        ];
        
    }
