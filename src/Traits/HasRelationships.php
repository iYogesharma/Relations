<?php
    
    namespace YS\Relations\Traits;
    
    use Illuminate\Database\Eloquent\Model;
    use Illuminate\Database\Eloquent\Builder;
    use YS\Relations\BelongsToManyThrough;
    
    trait HasRelationships
    {
        /**
         * Define a many-to-many relationship.
         *
         * @param  string  $related
         * @param  string  $through
         * @param  string  $foreighKey
         * @param  string  $primaryKey
         * @return \App\Models\Relations\BelongsToManyThrough
         */
        public function belongsToManyThrough($related, $through, $foreignRelatedKey = null, $foreignParentKey = null, $localKeyOne = null,$localKeyTwo= null)
        {
            
            $through = new $through;
            
            $foreignRelatedKey = $foreignRelatedKey ?: $through->getForeignKey();
            
            $foreignParentKey = $foreignParentKey ?: $through->getForeignKey();
            
            $localKeyOne = $localKeyOne ?: $through->getKeyName();
            
            $localKeyTwo = $localKeyTwo ?: $this->getKeyName();
            
            return $this->newBelongsToManyThroug(
                $this->newRelatedInstance($related)->newQuery(),
                $this, $through,$foreignRelatedKey,   $foreignParentKey,
                $localKeyOne,$localKeyTwo
            );
        }
        
        /**
         * Instantiate a new relations relationship.
         *
         * @param  \Illuminate\Database\Eloquent\Builder  $query
         * @param  \Illuminate\Database\Eloquent\Model  $farParent
         * @param  \Illuminate\Database\Eloquent\Model  $throughParent
         * @param  string  $foreighRelatedKey
         * @param  string  $foreighParentKey
         * @param  string  $localKeyOne
         * @param  string  $localKeyTwo
         * @return \App\Models\Relations\BelongsToManyThrough
         */
        protected function newBelongsToManyThroug(
            Builder $query, Model $farParent,
            Model $throughParent, $foreignRelatedKey, $foreignParentKey,$localKeyOne,$localKeyTwo
        )
        {
            return new BelongsToManyThrough(
                $query,$farParent, $throughParent,
                $foreignRelatedKey, $foreignParentKey,
                $localKeyOne,$localKeyTwo
            );
        }
        
    }
