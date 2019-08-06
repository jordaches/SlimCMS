<?php


use Slim\Http\Request;
use Slim\Http\Response;
use Workspace\Controllers\AdminUsers;
use Workspace\Controllers\Blog;
use Workspace\Controllers\Dashboard;
use Workspace\Controllers\Categories;
use Workspace\Controllers\Comment;
use Workspace\Library\EnforceLogin;
use Workspace\Library\Helpers;
use Interop\Container\ContainerInterface;

/*
$app->group('/auth',function (){
    $this->get('/login', AdminUsers::class . ':loginRenderAction')->setName('auth.loginRender');
    $this->post('/login', AdminUsers::class. ':loginPostAction')->setName('auth.login');
    $this->get('/logout', AdminUsers::class . ':logoutAction')->setName('auth.logout');
});*/

//Admin panel side

//Dashboard stuff
$app->group('/dashboard',function (){
    $this->get('', Dashboard::class . ':indexAction')->setName('dashboard.index'); //Working
    $this->get('/add',Dashboard::class . ':addAction')->setName('dashboard.add.get'); //Completed
    $this->post('/add',Dashboard::class . ':addAction')->setName('dashboard.add.post'); //image stuff to do
    $this->get('/delete/{id}',Dashboard::class . ':deleteAction')->setName('dashboard.delete'); //Working
    $this->get('/preview/{id}',Blog::class . ':fullpostAction'); //Done
    $this->get('/edit/{id}',Dashboard::class . ':editAction')->setName('dashboard.edit');
    $this->post('/edit/{id}',Dashboard::class . ':updateAction')->setName('dashboard.edit.update');
})->add(new EnforceLogin($container));

$app->group('/categories',function (){
    $this->get('',Categories::class . ':indexAction')->setName('categories.index');
    $this->post('/add',Categories::class . ':addAction')->setName('categories.add');
    $this->get('/delete/{id}',Categories::class . ':deleteAction')->setName('categories.delete');
})->add(new EnforceLogin($container));

$app->group('/admins',function (){
    $this->get('', AdminUsers::class . ':indexAction')->setName('admins.index');
    $this->post('/add',AdminUsers::class . ':addAction')->setName('admins.add')->add(new EnforceLogin($this->getContainer()));
    $this->get('/delete/{id}',AdminUsers::class . ':deleteAction')->setName('admins.delete')->add(new EnforceLogin($this->getContainer()));
    $this->post('/reset',AdminUsers::class . ':resetAction')->setName('admins.reset')->add(new EnforceLogin($this->getContainer()));
    $this->get('/login',AdminUsers::class . ':loginRenderAction')->setName('auth.loginRender');
    $this->post('/login',AdminUsers::class. ':loginPostAction')->setName('auth.login');
    $this->get('/logout',AdminUsers::class . ':logoutAction')->setName('auth.logout');
});

$app->group('/comments',function (){
    $this->get('',Comment::class . ':indexAction')->setName('comment.index');
    $this->get('/approve/{id}',Comment::class . ':approveAction')->setName('comment.approve');
    $this->get('/disapprove/{id}',Comment::class . ':disapproveAction')->setName('comment.disaprove');
    $this->get('/delete/{id}',Comment::class . ':deleteAction')->setName('comment.delete');
})->add(new EnforceLogin($container));

//User side
$app->get('/', Blog::class . ':indexAction')->setName('blog.index'); //Done / Paginate
$app->get('/index', Blog::class . ':indexAction')->setName('blog.index'); //Done / Paginate
$app->get('/index/{page}', Blog::class . ':indexAction')->setName('blog.pageindex'); //Done / Paginate
$app->post('/', Blog::class . ':searchpostAction')->setName('blog.search');//Done / Paginate
$app->get('/search/{category}', Blog::class . ':searchpostCatAction')->setName('blog.search.title');
$app->get('/{id}', Blog::class . ':fullpostAction')->setName('blogid.get');//Done / Paginate
$app->post('/{id}', Blog::class . ':addcommentAction')->setName('blogid.addcomment');//Done / Paginate

$app->group('/blog', function (){
    $this->get('/home/index[/{page}]',function ($req,$res,$args){
        Helpers::DebugDie($args);
    })->setName('home.index');
    $this->get('/home/post/{id}',function ($req,$res,$args){
        Helpers::DebugDie($args);
    })->setName('home.index');
    $this->get('/search/{term}[/{page}]',function ($req,$res,$args){
        Helpers::DebugDie($args);
    })->setName('search.index');
    $this->get('/categories/{category}[/{page}]',function ($req,$res,$args){
        Helpers::DebugDie($args);
    })->setName('search.index');
});