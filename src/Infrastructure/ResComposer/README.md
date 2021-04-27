## Overview
This is a library which gives ability to relatively easy join arrays (**resources** in terms of library) from different data sources (databases, APIs, etc).
There is a couple of interfaces you need to understand for library configuration.

### `ResourceDataLoader` - loader for resources. You need to implement it to load resources.

Contains method `public function load(array $ids): array;`

Where `$ids` - list of ids by which resources will be loaded.

Must return list of resources. Order of returned resources does not matter.

### `Link` - type of link between resources. One to one or one to many.
You probably do not need to implement this interface yourself. Two shipped is enough in most cases. 

But you need to know for configuration - constructor parameter for both is field of related resource (which is loaded by loader) by which resources will be grouped. So in case of OneToOne link you will get array of related resources with one resource in each group. In case of OneToMany link you will get array of related resources with many resources in each group.

### `PromiseCollector` - collects promises
Promise provides information about:
1. How to find id of main resource for join
2. How to write related resource to main resource.

Shipped providers is enough for most cases.

With `SimpleCollector` you can specify read key of main resource so promises will find id simply by field name and write key of main resource so promises will write loaded resource simply by field name.

With 

## Example 1. User has UserInfo
There is a User with id '1' and related UserInfo that contains this id, and some information about User (fullname, contacts etc).
```php
$user = ['id' => '1'];
$userInfo = [
    'userId' => '1',
    'fullname' => 'John Doe',
];    
```

The goal is to join User with related UserInfo

```php
// configuration is necessary for that the composer need information about how to join $user and $userInfo
$composer = new ResourceComposer();
$composer->registerConfig(
    'User', // name of the left resource to join.
    OneToOne('userId'), // Loader
    'UserInfo', // name of the right resource to join.
    new UserInfoDataLoader(),
    new SimpleCollector('id', 'userInfo'),
);
```
Get User from arbitrary store, for example database.
```php
$user = $userStore->findUserById('1');
$userWithInfo = $composer->composeOne($user, 'User');
```
$userWithInfo contains:
```
[
    'id' => '1',
    'userInfo' => [
        'userId' => '1',
        'fullname' => 'John Doe',
    ],
]
```
