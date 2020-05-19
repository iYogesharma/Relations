<?php
    
    namespace YS\Relations\Tests;
    
    use YS\Relations\BelongsToManyThrough;
    use YS\Relations\Tests\Models\City;
    use DB;
    
    class RelationTest extends TestCase
    {
        public function test_it_return_instance_of_belongs_to_many_through()
        {
            $this->withoutExceptionHandling();
            
            $pincodes = City::find(1)->pincodes();
            
            $this->asserttrue( $pincodes instanceof BelongsToManyThrough );
        }
    
        public function test_it_can_be_used_in_eager_loading()
        {
            $this->withoutExceptionHandling();
        
            $pincodes = City::with('pincodes')->find(1);
            
            $this->asserttrue( isset($pincodes['pincodes'][0]));
        }
    }
