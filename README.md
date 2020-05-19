# Relations

<p> This repository contains custom eloquent relations implemented in Laravel  </p>
<p>Currently threre is only one relation called Belongs To Mnany Through.
You can use it in model like any other eloquent relation.</p>


```php
    $this->belongsToManyThrough()
```

## Example

<p>Suppose you have three tables like :</p>

```php
    states
            id
            name
    
    cities 
            id 
            state_id 
            name 
            
    pincodes 
            id 
            pincode
            state_id
```
<p> and you want to get all the pincodes of the state to which city belong.
You can easily do this with belongsToManyThrough.Inside the city model you can write :</p>

```php
    return $this->belongsToManyThrough( Pincode::class,State::class );
```
<p>city has many pincodes through state </p>
<p> To use this relation you must use trait <b>YS\Relations\Traits\HasRelationships</b> in
 model you want to use this eloquent relation. </p>
