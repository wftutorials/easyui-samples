# Web development with EasyUI a jQuery library

In this tutorial we look at how we can use EasyUI. EasyUI is built on `jQuery` and host alot of easy to use
widgets that can speed up the web development process. 

# Installation

To install EasyUI go to the website download page which can be found [here](https://www.jeasyui.com/download/index.php)

Choose the jQuery option for download.

[eu_jquery_option.png]

Then choose the type of download you want. I used the Freeware edition for this tutorial.

[eu_download_options.png]

Once your download is finished you can take the files you need and put them in your project.

[eu_project_files.png]

For our usage we need the `jquery.easyui.min.js`, `jquery.min.js` and in `/themes/default/easyui.css`.

Create a file called `index.html` and place these resources in the head.

```html
<link rel="stylesheet" type="text/css" href="./css/default/easyui.css">
<script type="text/javascript" src="./assets/jquery.min.js"></script>
<script type="text/javascript" src="./assets/jquery.easyui.min.js"></script>
```

Now we add some basic easyUI code to see if our project works

```html
<h2>Basic Pagination</h2>
<p>The user can change page number and page size on page bar.</p>
<div style="margin:20px 0;"></div>
<div class="easyui-panel">
    <div class="easyui-pagination" data-options="total:114"></div>
</div>
```

The result you should see is shown below

[eu_installation_check.png]

That's it you have successfully installed easyUI. Now we can begin developing our web application.

The full code for the above example can be found here ->

```html
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Title</title>
    <link rel="stylesheet" type="text/css" href="./css/default/easyui.css">
    <script type="text/javascript" src="./assets/jquery.min.js"></script>
    <script type="text/javascript" src="./assets/jquery.easyui.min.js"></script>
</head>
<body>
<h2>Basic Pagination</h2>
<p>The user can change page number and page size on page bar.</p>
<div style="margin:20px 0;"></div>
<div class="easyui-panel">
    <div class="easyui-pagination" data-options="total:114"></div>
</div>
</body>
</html>
```

# Requirements

For this tutorial we used a `db.php` script to connect to the database. You
can view the script here ->

```php
<?php
function db_connect(){
    $host = "127.0.0.1";
    $user = "root";
    $password = "";
    $db = "wf_tutorials";
    try {
        $conn = new PDO("mysql:host=".$host.";dbname=".$db.';charset=utf8', $user, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $conn;
    }
    catch(PDOException $e) {
        die($e->getMessage());
    }
}
function query( $sql, $params=array()){
    $conn = db_connect();
    $query = $conn->prepare($sql);
    if(!empty($params)){
        $query->execute($params) or die ('Internal error');
    }else{
        $query->execute() or die ('Internal error');
    }
    return $query;
}
function fetch_all( $sql, $params=array() ){
    $query = query($sql, $params);
    try{
        $results = $query->fetchAll(PDO::FETCH_ASSOC);
    }catch (Exception $e){
        return $e->getMessage();
    }
    return $results;
}
?>
```

You will notice that we include this file in our example scripts when we are getting data from a database.

# Working with Lists

In web development lists are important. Lets see how we can create and interact with lists using easyUI.

Lets create a simple list. First we add the `html` template

```html
<div id="dl">
</div>
```

Now we load in in javascript.

```javascript
$('#dl').datalist({
    url: 'get_countries.php',
    checkbox: false,
    lines: true
});
```


This will give us the following result.

[eu_list.png]

You can access the `get_countries.php` scrip here -> 

```php
<?php
include 'db.php';

$countries = fetch_all('select * from countries');

$data = [];
foreach ($countries as $country){
    $data[] = [
        'value' => $country['country_name'],
        'text' => $country['country_name']
        ];
}
header('Content-Type: application/json');
echo json_encode($data);
```

You can view the full template for this example here ->
```html
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Working with EasyUI</title>
    <link rel="stylesheet" type="text/css" href="./css/default/easyui.css">
    <script type="text/javascript" src="./assets/jquery.min.js"></script>
    <script type="text/javascript" src="./assets/jquery.easyui.min.js"></script>
</head>
<body>
<h2>Data list</h2>
<p>Showing a list of data.</p>
<div style="margin:20px 0;"></div>
<div id="dl">
</div>
<script>
    $('#dl').datalist({
        url: 'get_countries.php',
        checkbox: false,
        lines: true
    });
</script>

</body>
</html>
```

## Clicking the list

Depending on how we plan to use the list we might need to click on the list. We can do this quite simply.

In javascript we attach a `onClickRow` event.

```javascript
$('#dl').datalist({
    url: 'get_countries.php',
    checkbox: false,
    lines: true,
    onClickRow : function(index, row){
        alert("this is " + row.text + " = " +row.value);
    }
});
```

This gives us the result.

[eu_click_list.gif]

The code can be found here ->

```html
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Working with EasyUI</title>
    <link rel="stylesheet" type="text/css" href="./css/default/easyui.css">
    <script type="text/javascript" src="./assets/jquery.min.js"></script>
    <script type="text/javascript" src="./assets/jquery.easyui.min.js"></script>
</head>
<body>
<h2>Data list</h2>
<p>Showing a list of data.</p>
<div style="margin:20px 0;"></div>
<div id="dl">
</div>
<script>
    $('#dl').datalist({
        url: 'get_countries.php',
        checkbox: false,
        lines: true,
        onClickRow : function(index, row){
            alert("this is " + row.text + " = " +row.value);
        }
    });
</script>
</body>
</html>
```

## Searching the list

Lets implement searching. First we add our `input` to get user query.

```html
<input id="query" class="easyui-textbox"  type="text"/><br><br>
```

In javascript our `datalist` remains the same.

```javascript
$('#dl').datalist({
    url: 'get_countries.php',
    checkbox: false,
    lines: true,
    onClickRow : function(index, row){
        alert("this is " + row.text + " = " +row.value);
    }
});
```

However our `input` is initialized via javascript

```javascript
$('#query').textbox({
    buttonText:'Search',
    iconCls:'icon-man',
    iconAlign:'left',
    onClickButton : function(e){
        var userQuery = $(this).val();
        console.log(userQuery);
        $('#dl').datalist('reload',{
            query : userQuery
        });
    }
})
```

We have a `onClickButton` function. When this is pressed we reload the list using
`#("#dl).datalist('reload')` and pass in the new query parameters. This allows us to update our list.

[eu_searching_list.gif]

You can view the full code here ->

```html
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Working with EasyUI</title>
    <link rel="stylesheet" type="text/css" href="./css/default/easyui.css">
    <script type="text/javascript" src="./assets/jquery.min.js"></script>
    <script type="text/javascript" src="./assets/jquery.easyui.min.js"></script>
</head>
<body>
<h2>Data list</h2>
<p>Showing a list of data.</p>
<div style="margin:20px 0;"></div>
<input id="query" class="easyui-textbox"  type="text"/><br><br>
<div id="dl">
</div>
<script>

    $('#dl').datalist({
        url: 'get_countries.php',
        checkbox: false,
        lines: true,
        onClickRow : function(index, row){
            alert("this is " + row.text + " = " +row.value);
        }
    });

    $('#query').textbox({
        buttonText:'Search',
        iconCls:'icon-man',
        iconAlign:'left',
        onClickButton : function(e){
            var userQuery = $(this).val();
            console.log(userQuery);
            $('#dl').datalist('reload',{
                query : userQuery
            });
        }
    })
</script>
</body>
</html>
```

## Checkboxes in the list

Sometimes you want to create a list to select multiple items. Lets see how we can achieve this.
We change the `checkbox` value to true is a great start.

```javascript
    $('#dl').datalist({
        url: 'get_countries.php',
        checkbox: true, // set to true
        singleSelect: false,  // set to false
        lines: true,
        onClickRow : function(index, row){
            alert("this is " + row.text + " = " +row.value);
        },
        onCheck: function(index, row){
            alert("This row is checked: " + row.value)
        }
    });
```

We also have a `onCheck` function where we can take action when a row is checked.

[eu_list_checkboxes.gif]

## Get selected checkboxes

We can get the selected rows quite easily. First we create a button to call that action.

```html
<a id="btn" href="#" class="easyui-linkbutton">Get all checked</a><br><br>
```

In javascript we call the `getChecked` function on the `datalist`

```javascript
$('#btn').on('click',function(){
    var checked = $('#dl').datalist('getChecked'); // get all checked rows
    for(var i=0; i<= checked.length-1; i++){
        console.log(checked[i].text); // show selected countries
    }
    return false;
})
```

We can iterate through the rows and display the text in the console. We can also clear the selection
by adding another button to take this action. We use the `clearChecked` method on the `datalist`.

```javascript
    $('#clear').on('click',function(){
        $('#dl').datalist('clearChecked');
        return false;
    });
```

[eu_list_get_selected.gif]

You can view the full code here ->

```html
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Working with EasyUI</title>
    <link rel="stylesheet" type="text/css" href="./css/default/easyui.css">
    <script type="text/javascript" src="./assets/jquery.min.js"></script>
    <script type="text/javascript" src="./assets/jquery.easyui.min.js"></script>
</head>
<body>
<h2>Data list</h2>
<p>Showing a list of data.</p>
<div style="margin:20px 0;"></div>
<input id="query" class="easyui-textbox"  type="text"/><br><br>
<a id="btn" href="#" class="easyui-linkbutton">Get all checked</a>
<a id="clear" href="#" class="easyui-linkbutton">Clear checked</a><br><br>
<div id="dl">
</div>
<script>

    $('#dl').datalist({
        url: 'get_countries.php',
        checkbox: true,
        singleSelect: false,
        lines: true,
        onClickRow : function(index, row){
            alert("this is " + row.text + " = " +row.value);
        },
        onCheck: function(index, row){
           // alert("This row is checked: " + row.value)
        }
    });

    $('#query').textbox({
        buttonText:'Search',
        iconCls:'icon-man',
        iconAlign:'left',
        onClickButton : function(e){
            var userQuery = $(this).val();
            console.log(userQuery);
            $('#dl').datalist('reload',{
                query : userQuery
            });
        }
    });

    $('#btn').on('click',function(){
        var checked = $('#dl').datalist('getChecked');
        for(var i=0; i<= checked.length-1; i++){
            console.log(checked[i].text); // show selected countries
        }
        return false;
    })

    $('#clear').on('click',function(){
        $('#dl').datalist('clearChecked');
        return false;
    });
    
</script>
</body>
</html>
```

# Tables

Tables are an important part of web development. Lets see how we can use `easyui` to work with tables.
We add the `html`

```html
<table id="dg" style="width:450px;"></table>
```

In the javascript we add

```javascript
$('#dg').datagrid({
    url:'get_users.php',
    fitColumns:true,
    columns:[[
        {field:'firstname',title:'First Name'},
        {field:'lastname',title:'Last Name'},
        {field:'gender',title:'Gender'},
        {field:'email',title:'Email'},
        {field:'phone',title:'Phone'},
    ]]
});
```

The `url` is where we get the data.

You can view the `get_users.php` script here ->

```php
<?php
include 'db.php';

$users = fetch_all('select * from users');

$data = [];
foreach ($users as $user){
    $data[] = [
        'firstname' => $user['first_name'],
        'lastname' => $user['last_name'],
        'gender' => $user['gender'],
        'age' => $user['age'],
        'email' => $user['email'],
        'phone' => $user['phone']
        ];
    //echo $country['country_name'];
}
header('Content-Type: application/json');
echo json_encode($data);
```

The results is show below

[eu_table_view.png]

## Click on a table row

Lets take a action when we click a row. We use the `onClickRow` event.

```javascript
$('#dg').datagrid({
    url:'get_users.php',
    fitColumns:true,
    columns:[[
        {field:'firstname',title:'First Name'},
        {field:'lastname',title:'Last Name'},
        {field:'gender',title:'Gender'},
        {field:'email',title:'Email'},
        {field:'phone',title:'Phone'},
    ]],
    onClickRow : function(index, row){
        alert(row.firstname + ' ' + row.lastname); // on row click
    }
});
```

Its that simple. The results is shown below.

[eu_table_row_click.gif]

You can take any action you want within this function.

## Table Pagination

Lets add pagination to our datatable. This will allow use to display only a section at a time.
In the javascript its pretty straight forward. Just add `pagination` in the object settings.

```javascript
$('#dg').datagrid({
    url:'get_users.php',
    fitColumns:true,
    pagination: true, // allow for pagination
    columns:[[
        {field:'firstname',title:'First Name'},
        {field:'lastname',title:'Last Name'},
        {field:'gender',title:'Gender'},
        {field:'email',title:'Email'},
        {field:'phone',title:'Phone'},
    ]],
    onClickRow : function(index, row){
        alert(row.firstname + ' ' + row.lastname);
    }
});
```

The `php` script is where all the work goes. Lets check it out.

```php
<?php
include 'db.php';

$limit = 10; // page limit
$page = isset($_POST['page']) ? intval($_POST['page']) : 1; // page sent from easyui
$totalRows = isset($_POST['rows']) ? intval($_POST['rows']) : $limit; // limit from easyui
$offset = ($page-1) * $totalRows; // create offset from page
$users = fetch_all("select * from users LIMIT $totalRows OFFSET $offset"); // query
$total = fetch_all('Select count(*) as total from users')[0]['total']; // get total
$response = []; // respose object
$data = [];
foreach ($users as $user){
    $data[] = [
        'firstname' => $user['first_name'],
        'lastname' => $user['last_name'],
        'gender' => $user['gender'],
        'age' => $user['age'],
        'email' => $user['email'],
        'phone' => $user['phone']
        ];
    //echo $country['country_name'];
}
$response['total'] = $total; // add total to response object
$response['page'] = $page; // add page to response object
$response['rows'] = $data; // add data to response object
header('Content-Type: application/json');
echo json_encode($response); // send as json
```

Now once you understand the script above our datatable will work fine. `easyui` sends `page` and `rows` to the
server `url` you provided. The `response` object holds our data. 

The results are show below.

[eu_table_pagination.gif]

Learn more [here](https://www.jeasyui.com/tutorial/datagrid/datagrid2.php).

Our row click still works even when the pagination is used.

## Table Searching

Lets look at search or filtering using the `datagrid`.
We add our `html` elements at search box

```html
<input id="query" class="easyui-textbox"  type="text"/><br><br>
```

We used this before. We will initialize it just the same way.

```javascript
$('#query').textbox({
    buttonText:'Search',
    iconCls:'icon-man',
    iconAlign:'left',
    onClickButton : function(e){
        var userQuery = $(this).val();
        $('#dg').datalist('reload',{
            query : userQuery
        });
    }
});
```

On the `textbox` search butto click we reload the `datagrid` with some search params from the `textbox`.

We can make updates to the `get_users.php` script like. To facilitate the server side searching

```php
$query = isset($_POST['query']) ? $_POST['query'] : null;
if($query){
    $users = fetch_all("select * from users WHERE first_name LIKE ? LIMIT $totalRows OFFSET $offset",['%'.$query.'%']);
    $total = fetch_all('Select count(*) as total from users WHERE first_name LIKE ? ',['%'.$query.'%'])[0]['total'];
}else{
    $users = fetch_all("select * from users LIMIT $totalRows OFFSET $offset");
    $total = fetch_all('Select count(*) as total from users')[0]['total'];
}
```

The results can be seen below.

[eu_table_searching.gif]

You can view the `html` file here -->

The full html script
```html
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Working with EasyUI</title>
    <link rel="stylesheet" type="text/css" href="./css/default/easyui.css">
    <script type="text/javascript" src="./assets/jquery.min.js"></script>
    <script type="text/javascript" src="./assets/jquery.easyui.min.js"></script>
</head>
<body>
<h2>Show DataTable</h2>
<p>Showing a table of data.</p>
<input id="query" class="easyui-textbox"  type="text"/><br><br>
<table id="dg" style="width:500px;"></table>
<script>
$('#dg').datagrid({
    url:'get_users.php',
    fitColumns:true,
    pagination: true,
    columns:[[
        {field:'firstname',title:'First Name'},
        {field:'lastname',title:'Last Name'},
        {field:'gender',title:'Gender'},
        {field:'email',title:'Email'},
        {field:'phone',title:'Phone'},
    ]],
    onClickRow : function(index, row){
        alert(row.firstname + ' ' + row.lastname);
    }
});

$('#query').textbox({
    buttonText:'Search',
    iconCls:'icon-man',
    iconAlign:'left',
    onClickButton : function(e){
        var userQuery = $(this).val();
        console.log(userQuery);
        $('#dg').datagrid('reload',{
            query : userQuery
        });
    }
});
</script>
</body>
</html>
```

The full `get_users.php` script

```php
<?php
include 'db.php';

$limit = 10;
$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
$totalRows = isset($_POST['rows']) ? intval($_POST['rows']) : $limit;
$offset = ($page-1) * $totalRows;
$query = isset($_POST['query']) ? $_POST['query'] : null;
if($query){
    $users = fetch_all("select * from users WHERE first_name LIKE ? LIMIT $totalRows OFFSET $offset",['%'.$query.'%']);
    $total = fetch_all('Select count(*) as total from users WHERE first_name LIKE ? ',['%'.$query.'%'])[0]['total'];
}else{
    $users = fetch_all("select * from users LIMIT $totalRows OFFSET $offset");
    $total = fetch_all('Select count(*) as total from users')[0]['total'];
}
$response = [];
$data = [];
foreach ($users as $user){
    $data[] = [
        'firstname' => $user['first_name'],
        'lastname' => $user['last_name'],
        'gender' => $user['gender'],
        'age' => $user['age'],
        'email' => $user['email'],
        'phone' => $user['phone']
        ];
    //echo $country['country_name'];
}
$response['total'] = $total;
$response['page'] = $page;
$response['rows'] = $data;
header('Content-Type: application/json');
echo json_encode($response);
```

# Creating Trees

In easyui framework we can create awesome trees. Lets see how.
All we need to do is add some `html`.

```html
<ul id="tt" class="easyui-tree">
    <li>
        <span>Employees</span>
        <ul>
            <li>
                <span>HR</span>
                <ul>
                    <li><span><a data-id="1">Jerrod Mcdow</a></span></li>
                    <li><span><a data-id="2">Sonya Sauage</a></span></li>
                    <li><span><a data-id="3">Bennie Clover</a></span></li>
                </ul>
            </li>
            <li>
                <span>Engineering</span>
                <ul>
                    <li><span><a data-id="4">Alfonso Cohee</a></span></li>
                    <li><span><a data-id="5">Mitchel Ruggles</a></span></li>
                    <li><span><a data-id="6">Anamaria Taranto</a></span></li>
                </ul>
            </li>
            <li>
                <span>Sales</span>
                <ul>
                    <li><span><a data-id="7">Chan Toppin</a></span></li>
                    <li><span><a data-id="8">Anamaria Taranto</a></span></li>
                    <li><span><a data-id="9">Shaquana Sinquefield</a></span></li>
                </ul>
            </li>
        </ul>

    </li>
</ul>
```

This results in the following

[eu_tress_widget.png]

We can create take actions when we click on a note. Lets see how

```javascript
$('#tt').tree({
    onClick: function(node){
        var el = $(node.text);
        console.log(el.attr('data-id'));
    }
});
```

We can call `onClick` on the `tree` and we have access to the `node`. We create an element using `jQuery`
from the `node.text` value.

```javascript
$(node.text)
```

Whatever we have inside the `a` element we can now access it and take action.
Or we can just take the text from the `a`.

```javascript
$('#tt').tree({
    onClick: function(node){
        var el = $(node.text);
        alert(el.text());
    }
});
```

[eu_tree_onclick.gif]

You can see the full script here ->

```html
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Working with EasyUI</title>
    <link rel="stylesheet" type="text/css" href="./css/default/easyui.css">
    <script type="text/javascript" src="./assets/jquery.min.js"></script>
    <script type="text/javascript" src="./assets/jquery.easyui.min.js"></script>
</head>
<body>
<h2>Show Trees</h2>
<p>Showing a list of trees.</p>
<ul id="tt" class="easyui-tree">
    <li>
        <span>Employees</span>
        <ul>
            <li>
                <span>HR</span>
                <ul>
                    <li><span><a data-id="1">Jerrod Mcdow</a></span></li>
                    <li><span><a data-id="2">Sonya Sauage</a></span></li>
                    <li><span><a data-id="3">Bennie Clover</a></span></li>
                </ul>
            </li>
            <li>
                <span>Engineering</span>
                <ul>
                    <li><span><a data-id="4">Alfonso Cohee</a></span></li>
                    <li><span><a data-id="5">Mitchel Ruggles</a></span></li>
                    <li><span><a data-id="6">Anamaria Taranto</a></span></li>
                </ul>
            </li>
            <li>
                <span>Sales</span>
                <ul>
                    <li><span><a data-id="7">Chan Toppin</a></span></li>
                    <li><span><a data-id="8">Anamaria Taranto</a></span></li>
                    <li><span><a data-id="9">Shaquana Sinquefield</a></span></li>
                </ul>
            </li>
        </ul>

    </li>
</ul>
<script>
    $('#tt').tree({
        onClick: function(node){
            var el = $(node.text);
            alert(el.text());
        }
    });
</script>
</body>
</html>
```

# Creating Dialogs

How can we create dialogs in easyui. Lets see how.

```html
<a id="btn" href="#" class="easyui-linkbutton">Open dialog</a>
<div id="dd">Dialog Content.</div>
```

We have a `button` and the the content `div` by `id` **dd**. In the javascript we do

```javascript
$('#dd').dialog({
    title: 'My Dialog',
    width: 400,
    height: 200,
    closed: true, // closed currently
    cache: false,
    href: 'get_content.php', // get content ajax. php script
    modal: true
});
```

Now when we click the button we one to open our `dialog`

```javascript
$('#btn').on('click',function(){
    $('#dd').dialog('open');
});
```

Lets see how this works.

[eu_dialog_widget.gif]

You can access the full code here ->

```html
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Working with EasyUI</title>
    <link rel="stylesheet" type="text/css" href="./css/default/easyui.css">
    <script type="text/javascript" src="./assets/jquery.min.js"></script>
    <script type="text/javascript" src="./assets/jquery.easyui.min.js"></script>
</head>
<body>
<h2>Show Dialog</h2>
<p>Showing a dialog on click</p>
<a id="btn" href="#" class="easyui-linkbutton">Open dialog</a>
<div id="dd">Dialog Content.</div>
<script>
    $('#dd').dialog({
        title: 'My Dialog',
        width: 400,
        height: 200,
        closed: true,
        cache: false,
        href: 'get_content.php',
        modal: true
    });
    $('#btn').on('click',function(){
        $('#dd').dialog('open');
    });
</script>
</body>
</html>
```

# Menus

We can create menus quite easily. Lets see how.
First we need to add the `icons.css` and the `icons` folder

```html
<link rel="stylesheet" type="text/css" href="./css/icon.css">
```

Now lets create our menu.

```html
<div class="easyui-panel" style="padding:5px;">
    <a href="#home" class="easyui-linkbutton" data-options="plain:true">Home</a>
    <a href="#" class="easyui-menubutton" data-options="iconCls:'icon-edit'">Edit</a>
    <a href="#" class="easyui-menubutton" data-options="iconCls:'icon-help'">Help</a>
    <a href="#close" class="easyui-linkbutton" data-options="plain:true, iconCls:'icon-cancel'">Close</a>
</div>
```

This gives us the following result

[eu_menu.png]

Some things to note. The `home` and `exit` options are special. In `data-options` we added a `plain:true` attribute.
We also changed their classes to `easyui-linkbutton`. So they dont have dropdown arrows.

For the rest lets add dropdown menus.

In the `edit` dropdown we want to add three options.

```html
<div id="mm1" style="width:150px;">
    <div data-options="iconCls:'icon-undo'">Undo</div>
    <div data-options="iconCls:'icon-redo'">Redo</div>
    <div class="menu-sep"></div>
    <div data-options="iconCls:'icon-remove'">Delete</div>
</div>
```

Notice we have a `div` with class `menu-sep` which gives us the separator.

[eu_dropdown_menu.gif]

For the `edit` menu we add the dropdown menu by add a `menu:'#mm1'` in the `data-options`

```html
<a href="#" class="easyui-menubutton" data-options="menu:'#mm1', iconCls:'icon-edit'">Edit</a>
```

## Taking action on menus

Remember all these are elements. We can add `ids` and set `onClick` attributes on them.
For e.g

```html
<div id="delete-option" data-options="iconCls:'icon-remove'">Delete</div>
```

In javascript we can do

```javascript
$('#delete-option').on('click',function(){
    alert("delete something");
    return false;
})
```

[eu_menu_on_click.gif]

The full script can be seen below ->

```html
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Working with EasyUI</title>
    <link rel="stylesheet" type="text/css" href="./css/default/easyui.css">
    <link rel="stylesheet" type="text/css" href="./css/icon.css">
    <script type="text/javascript" src="./assets/jquery.min.js"></script>
    <script type="text/javascript" src="./assets/jquery.easyui.min.js"></script>
</head>
<body>
<h2>Show Menus</h2>
<p>Showing a menu</p>
<div class="easyui-panel" style="padding:5px;">
    <a href="#" class="easyui-linkbutton" data-options="plain:true">Home</a>
    <a href="#" class="easyui-menubutton" data-options="menu:'#mm1', iconCls:'icon-edit'">Edit</a>
    <a href="#" class="easyui-menubutton" data-options="iconCls:'icon-help'">Help</a>
    <a href="#" class="easyui-linkbutton" data-options="plain:true, iconCls:'icon-cancel'">Close</a>
</div>

<div id="mm1" style="width:150px;">
    <div data-options="iconCls:'icon-undo'">Undo</div>
    <div data-options="iconCls:'icon-redo'">Redo</div>
    <div class="menu-sep"></div>
    <div id="delete-option" data-options="iconCls:'icon-remove'">Delete</div>
</div>
<script>
    $('#delete-option').on('click',function(){
        alert("delete something");
        return false;
    })
</script>
</body>
</html>
```

## Context menus

We can create context menus in easyui. Lets see how.
We add the `html`

```html
<div id="mm" class="easyui-menu" style="width:120px;">
    <div data-options="iconCls:'icon-save'">View</div>
    <div>Edit</div>
    <div>Delete</div>
    <div>Exit</div>
</div>
```

Notice in the `view` menu we add and `icon` using `data-options`.

In javascript we can do

```javascript
$(document).on('contextmenu',function(e){
    e.preventDefault(); // prevent default
    $('#mm').menu('show', { // call the menu by id
        left: e.pageX,
        top: e.pageY
    });
});
```

The result is show below. You can learn more about it [here](https://www.jeasyui.com/demo/main/index.php?plugin=Menu&theme=material-teal&dir=ltr&pitem=&sort=asc).

[eu_context_menu.gif]

The full code can be found here ->

```html
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Working with EasyUI</title>
    <link rel="stylesheet" type="text/css" href="./css/default/easyui.css">
    <link rel="stylesheet" type="text/css" href="./css/icon.css">
    <script type="text/javascript" src="./assets/jquery.min.js"></script>
    <script type="text/javascript" src="./assets/jquery.easyui.min.js"></script>
</head>
<body>
<h2>Show Menus</h2>
<p>Showing a menu</p>
<div id="mm" class="easyui-menu" style="width:120px;">
    <div data-options="iconCls:'icon-save'">View</div>
    <div>Edit</div>
    <div>Delete</div>
    <div>Exit</div>
</div>
<script>
$(document).on('contextmenu',function(e){
    e.preventDefault();
    $('#mm').menu('show', {
        left: e.pageX,
        top: e.pageY
    });
});
</script>
</body>
</html>
```

# Forms

Lets look at some `form` elements using easyui.

# Text Input

Lets create the `html`

```html
<input id="tb" style="width:300px">
```

The javascript is show below

```javascript
$('#tb').textbox({
    iconCls:'icon-search',
    iconAlign:'left',
})
```

Lets add an `event` `onChange`

```javascript
$('#tb').textbox({
    iconCls:'icon-search',
    iconAlign:'left',
    onChange : function(updated, old){ //updated and old values
        console.log(updated);
    }
})
```

[eu_text_input.gif]

We can add a `button`

```javascript
$('#tb').textbox({
    buttonText:'Search',
    iconCls:'icon-search',
    iconAlign:'left',
    onChange : function(updated, old){
        console.log(updated);
    }
})
```

[eu_textinput_with_button.png]

We can add a `onclick` event on this button.

```javascript
$('#tb').textbox({
    buttonText:'Search',
    iconCls:'icon-search',
    iconAlign:'left',
    onChange : function(updated, old){
        console.log(updated);
    },
    onClickButton : function(){
        alert('the button was pressed')
    }
})
```

The results are show below.

[eu_onbuttonclick.gif]

# Conclusion


