<?php
    
    namespace YS\Relations\Tests;
    
    use YS\Relations\Tests\Models\State;
    use YS\Relations\Tests\Models\City;
    use YS\Relations\Tests\Models\Pincode;
    use Illuminate\Database\Schema\Blueprint;
    use Orchestra\Testbench\TestCase as BaseTestCase;
    
    abstract class TestCase extends BaseTestCase
    {
        protected function setUp() : void
        {
            parent::setUp();
            
            $this->migrateDatabase();

            $this->seedDatabase();
    
        }
        protected function migrateDatabase()
        {
            /** @var \Illuminate\Database\Schema\Builder $schemaBuilder */
            $schemaBuilder = $this->app['db']->connection()->getSchemaBuilder();
            if (! $schemaBuilder->hasTable('cities')) {
                $schemaBuilder->create('cities', function (Blueprint $table) {
                    $table->increments('id');
                    $table->string('name');
                    $table->unsignedInteger('state_id');
                    $table->timestamps();
                });
            }
            if (! $schemaBuilder->hasTable('states')) {
                $schemaBuilder->create('states', function (Blueprint $table) {
                    $table->increments('id');
                    $table->string('name');
                    $table->timestamps();
                });
            }
            if (! $schemaBuilder->hasTable('pincodes')) {
                $schemaBuilder->create('pincodes', function (Blueprint $table) {
                    $table->increments('id');
                    $table->unsignedInteger('state_id');
                    $table->string('pincode');
                    $table->timestamps();
                });
            }
        }
    
        protected function seedDatabase()
        {
        
            collect(range(1, 10))->each(function ($i)  {
                 State::query()->create([
                    'name' => 'State-' . $i,
                ]);
            
                collect(range(1, 10))->each(function ($i) {
                    City::query()->create([
                        'name'  => 'City-' . $i,
                        'state_id'=>1
                    ]);
                });
                collect(range(1, 10))->each(function ($i) {
                    Pincode::query()->create([
                        'pincode'  => 17100 . $i,
                        'state_id'=>1
                    ]);
                });
            });
        }
    
        /**
         * Set up the environment.
         *
         * @param \Illuminate\Foundation\Application $app
         */
        protected function getEnvironmentSetUp($app)
        {
            $app['config']->set('app.debug', true);
            $app['config']->set('database.default', 'sqlite');
            $app['config']->set('database.connections.sqlite', [
                'driver'   => 'sqlite',
                'database' => ':memory:',
                'prefix'   => '',
            ]);
        }
    }
