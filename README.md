# Laravel HoneyPot validation

This will generate honeypot validation fields that can be easily inserted into any form.

## Credit
I want to give credit to Arjhay Delos Santos <devarjhay> for this awesome repo: [https://github.com/devarjhay/honeypot](https://github.com/devarjhay/honeypot) from which a lot of this package is based on.

## How to use?

This package is different than the ones I've seen before becuase it allows for the random generation of input names that then get stored in session storage. Thus there are two ways you can use this package.
    1) By not passing any argument to the honeypot accessor
    2) By passing a name to use for the inputs in your honeypot accessor

### Option 1:
#### In View:

1) Blade Directive
```
@honeypot
```

2) Facade
```
{!! HoneyPot::make() !!}
```

This will generate something that looks like this:
```
<div class="wrap" style="display: none;">
    <input name="ifipim-browser-favorite" type="text" autocomplete="off" value="">
    <input name="ifipim-browser-favorite_time" type="text" autocomplete="off" value="eyJpdiI6Ijl5cVA5djNmZGFua3Z3QWZjbW5QM3c9PSIsInZhbHVlIjoiYVdlUTU3VllYVkpxU0J0OG9pMFZIZz09IiwibWFjIjoiYmM0Mzc5NGQ4ZmIzMWZhYTY4MDc5MWMyMzQwMjliZGMyMTUxZTdiYWFlODg5YTQ1ZTAxZTZlMzY4NmZiOWZiNSJ9">
</div>
```
In the example above, `ifipim-browser-favorite` was a randomly generated name.

#### Validation
Now you need to add the validation rules to your form request. For this I provided a neat function called `honeypot_add_rules` that you can wrap round your rules array and returns a new array. This will get all of the honeypot names that were stored in storage and generate the rules for them like so. It can be used like so:

```
# RegisterController.php
...
    protected function validator(array $data)
    {
        $rules = honeypot_add_rules([
            'username' => 'required|string|max:255|unique:users',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed|strong_password',
        ]);
        return Validator::make($data, $rules);
    }
....
```

### Option 2:
#### In View:
1) Blade Directive
```
@honeypot(my_honeypot)
```

2) Facade
```
{!! HoneyPot::make('my_honeypot') !!}
```

This will generate something that looks like this:
```
<div class="wrap" style="display: none;">
    <input name="my_honeypot" type="text" autocomplete="off" value="">
    <input name="my_honeypot_time" type="text" autocomplete="off" value="eyJpdiI6Ijl5cVA5djNmZGFua3Z3QWZjbW5QM3c9PSIsInZhbHVlIjoiYVdlUTU3VllYVkpxU0J0OG9pMFZIZz09IiwibWFjIjoiYmM0Mzc5NGQ4ZmIzMWZhYTY4MDc5MWMyMzQwMjliZGMyMTUxZTdiYWFlODg5YTQ1ZTAxZTZlMzY4NmZiOWZiNSJ9">
</div>
```
In the examples above, `my_honeypot` was used to create the names for the input.

#### Validation
Now you need to add the validation rules to your form request. The necessary rules to add are `honeypot` and `honeypot_time`.

```
# RegisterController.php
...
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'username' => 'required|string|max:255|unique:users',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed|strong_password',
            'my_honeypot' => 'honeypot',
            'my_honeypot_time' => 'honeypot_time:5'
        ]);
    }
....
```

Here we add the names of the input fields for the honeypot and tell it to use the validation rules `honeypot` and `honeypot_time` respectively. The `honeypot` rule takes no arguments. If the value is not empty, then it will fail. The `honeypot_time` rule does take a single argumeny. That is the time in seconds from which the form was created to which the form was submitted. This option is *optional*. There is a default that you can provide for this function in config. If there is no default in config, the package will fallback to 3 seconds.


## How is this different?

This package provides a means to create sesison based field names for your honey pot fields.
