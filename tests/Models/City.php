<?php
    
    namespace YS\Relations\Tests\Models;
    
    use Illuminate\Database\Eloquent\Model;
    use YS\Relations\Traits\HasRelationships;
    
    class City extends Model
    {
        use HasRelationships;
        
        /**
         * The name of table model is associated with.
         *
         * @var string
         */
        protected $table='cities';
        
        /**
         * The attributes that are mass assignable.
         *
         * @var array
         */
        protected $fillable = [
            'name','state_id'
        ];
        
        public function pincodes()
        {
            return $this->belongsToManyThrough(Pincode::class,State::class );
        }
    }
