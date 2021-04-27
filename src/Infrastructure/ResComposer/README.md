## Overview
This is a library which goal is to simplify arrays (**resources** in terms of library) joins from different data sources (databases, APIs, etc).

There is two resource types:
1. **related resource** - will be joined to main resource.
2. **main resource** - resource to which related resource will be joined.

For example - you have **main resource**, let's call it User, which is stored in document database.
When loaded from database it will look like this: 
```php
$user = [
    'id' => '1',
    'is_active' => true,
];
```

And **related resource**, let's call it UserInfo, which is stored in relational database.
It will look like this:
```php
$userInfo = [
    'user_id' => '1',
    'fullname' => 'John Doe',
];
```
Obviously it is not possible to easy join this **resources** from different databases by standard SQL join, but it is possible to do this join on application side (so-called application side joins).

This is just an example and it is easy to assign UserInfo to User, but in real world scenarios there will be list of users with multiple related type of **resources** which will have nested **resources** and you will have to write cycles in cycles and recursive functions every single time.

To join User with UserInfo you need to:

1. Write loader for **related resource** (UserInfo in this case).To achieve this you must implement `ResourceDataLoader` interface.
```php
final class UserInfoLoader implements ResourceDataLoader
{
    private Connection $db;

    public function __construct(Connection $db)
    {
        $this->db = $db;
    }

    public function load(array $userIds): array
    {
        $userInfos = $this->db->fetchAllAssociative(
            'select * from user_info where user_info.user_id in (:user_ids)',
            ['user_ids' => $userIds],
            ['user_ids' => Connection::PARAM_STR_ARRAY],
        );

        return $userInfos;
    }
}
```

2. Choose how User and UserInfo related to each other and by what field UserInfo will be joined.
3. Choose by what field in User you want to make join and to what field in User you want to join UserInfo.
4. Configure library accordingly and call `composeOne` with resource and its name.
```php
$composer = new ResourceComposer();
$composer->registerConfig(
    'User', // name of the main resource to join.
    OneToOne('user_id'), // User has one UserInfo and UserInfo will be joined by user_id
    'UserInfo', // name of the related resource to join.
    new UserInfoLoader($connection), // loader for UserInfo
    new SimpleCollector(
        'id', // field in User by which join will took place
        'userInfo', // field in User to which related resource will be written
    ),
);
$userWithInfo = $composer->composeOne($user, 'User');
```
$userWithInfo will contains
```php
[
    'id' => '1',
    'userInfo' => [
        'userId' => '1',
        'fullname' => 'John Doe',
]
```
