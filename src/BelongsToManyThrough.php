<?php
    
    namespace YS\Relations;
    
    use Illuminate\Database\Eloquent\Relations\Relation;
    use Illuminate\Database\Eloquent\SoftDeletes;
    use \Illuminate\Database\Eloquent\Builder ;
    use Illuminate\Database\Eloquent\Model;
    
    class BelongsToManyThrough extends Relation
    {
        /**
         * The "through" parent model instance.
         *
         * @var \Illuminate\Database\Eloquent\Model
         */
        protected $throughParent;
        
        /**
         * The far parent model instance.
         *
         * @var \Illuminate\Database\Eloquent\Model
         */
        protected $farParent;
        
        /**
         * The foreign key of through model in parent model.
         *
         * @var string
         */
        protected $parentForeignKey;
        
        /**
         * The foreign key of through model in related model.
         *
         * @var string
         */
        protected $relatedForeignKey;
        
        /**
         * The primary key on the through model.
         *
         * @var string
         */
        protected $throughPrimaryKey;
        
        /**
         * The primary key on the parent model.
         *
         * @var string
         */
        protected $primaryKey;
        
        
        public function  __construct(
            Builder $query,Model $farParent ,
            Model $throughParent, $foreignRelatedKey,
            $foreignParentKey, $localKeyOne, $localKeyTwo
        )
        {
            
            $this->throughParent = $throughParent;
            
            $this->parentForeignKey =  $foreignParentKey;
            
            $this->relatedForeignKey =  $foreignParentKey;
            
            $this->throughPrimaryKey= $localKeyOne;
            
            $this->primaryKey= $localKeyTwo;
            
            parent::__construct( $query, $farParent );
        }
        
        /**
         * Set the base constraints on the relation query.
         *
         * @return void
         */
        public function addConstraints()
        {
            $this->performJoins();
            if(static::$constraints){
                $this->addWhereConstraints();
            }
            
        }
        
        /**
         * Set the where clause for the relation query.
         *
         * @param  \Illuminate\Database\Eloquent\Builder|null  $query
         *
         * @return $this
         */
        protected function performJoins( $query = NULL)
        {
            $query = $query ?: $this->query;
            
            // We need to join to the intermediate table on the related model's primary
            // key column with the intermediate table's foreign key for the related
            // model instance. Then we can set the "where" for the parent models.
            $farkey = $this->getQualifiedThroughKeyName();
            
            $query->join( $this->throughParent->getTable(),$this->getQualifiedRelatedKeyName(),'=',$farkey)
                ->join($this->parent->getTable(), $this->getQualifiedForeignParentKeyName(),'=',$farkey)
                ->select($this->related->getTable().".*");
            if ($this->throughParentSoftDeletes()) {
                $query->whereNull($this->throughParent->getQualifiedDeletedAtColumn());
            }
        }
        
        /**
         * Set the where clause for the relation query.
         *
         * @return $this
         */
        protected function addWhereConstraints()
        {
            $this->query->where(
                $this->getQualifiedParentKeyName(), '=', $this->parent->{$this->primaryKey}
            );
        }
        
        /**
         * Determine whether "through" parent of the relation uses Soft Deletes.
         *
         * @return bool
         */
        public function throughParentSoftDeletes()
        {
            return in_array(SoftDeletes::class, class_uses_recursive(
                get_class($this->throughParent)
            ));
        }
        
        /**
         * Get the fully qualified parent rlated foreign key name.
         *
         * @return string
         */
        public function getQualifiedForeignParentKeyName()
        {
            return $this->parent->qualifyColumn($this->parentForeignKey);
        }
        
        /**
         * Get the fully qualified parent key name.
         *
         * @return string
         */
        public function getQualifiedParentKeyName()
        {
            return $this->parent->qualifyColumn($this->primaryKey);
        }
        /**
         * Get the fully qualified related key name.
         *
         * @return string
         */
        public function getQualifiedRelatedKeyName()
        {
            return $this->related->qualifyColumn($this->relatedForeignKey);
        }
        
        /**
         * Get the fully qualified through parent key name.
         *
         * @return string
         */
        public function getQualifiedThroughKeyName()
        {
            return $this->throughParent->qualifyColumn($this->throughPrimaryKey);
        }
        
        /**
         * Set the constraints for an eager load of the relation.
         *
         * @param array $models
         *
         * @return void
         */
        public function addEagerConstraints(array $models)
        {
            $this->query->whereIn(
                $this->getQualifiedParentKeyName(),
                collect($models)->pluck($this->primaryKey)
            );
            
        }
        
        /**
         * Initialize the relation on a set of models.
         *
         * @param array $models
         * @param string $relation
         *
         * @return array
         */
        public function initRelation(array $models, $relation)
        {
            
            foreach ($models as $model) {
                $model->setRelation(
                    $relation,
                    $this->related->newCollection()
                );
            }
            return $models;
        }
        
        /**
         * Match the eagerly loaded results to their parents.
         *
         * @param array $models
         * @param \Illuminate\Database\Eloquent\Collection $results
         * @param string $relation
         *
         * @return array
         */
        public function match(array $models, \Illuminate\Database\Eloquent\Collection $results, $relation)
        {
            if ($results->isEmpty()) {
                return $models;
            }
            
            foreach ($models as $model) {
                $model->setRelation(
                    $relation,
                    $results->filter(function ($related) use ($model) {
                        return $related;
                    })
                );
            }
            return $models;
            
        }
        
        /**
         * Get the results of the relationship.
         *
         * @return mixed
         */
        public function getResults()
        {
            return $this->get();
        }
    }