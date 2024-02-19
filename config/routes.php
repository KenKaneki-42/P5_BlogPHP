<?php

use Core\Router;
use Pecee\Http\Request;

Router::setDefaultNamespace("App\Controller");

//------ HOME -----//
Router::get("/homepage", "HomepageController@homepage");
// --- Authentication
Router::all("/inscription", "RegisterController@register")->setName('inscription');
Router::all("/connexion", "RegisterController@login");
Router::all("/deconnexion", "RegisterController@logout");
Router::all("/inscription-confirmation/{token}", "RegisterController@validationToken");
// ---- CONTACT EMAIL ---- //
// un controller ou un handler mail ou les deux?
Router::post("/send-email-contact", "MailerController@sendContentContactForm");
// ------ FRONT POSTS -----
Router::get("/articles", "Front\PostController@index");
Router::get("/articles/{id}", "Front\PostController@show");

// ------ ADMIN COMMENTS -----
Router::get("/admin/commentaires", "Admin\CommentController@index");
Router::all("/admin/commentaire/{id}/{status}/{csrfToken}", "Admin\CommentController@changeStatus");
// ------ ADMIN POSTS -----

// Router::get("/admin/posts", "Admin\PostController@index")->setName('admin_post_index');
// Router::get("/admin/posts/create", "Admin\PostController@create")->setName('admin_post_create');
// Router::post("/admin/posts", "Admin\PostController@store")->setName('admin_post_store');
// Router::get("/admin/posts/{id}/edit", "Admin\PostController@edit")->setName('admin_post_edit');
// Router::put("/admin/posts/{id}", "Admin\PostController@update")->setName('admin_post_update');
// Router::delete("/admin/posts/{id}", "Admin\PostController@destroy")->setName('admin_post_destroy');

Router::get("/admin/articles", "Admin\PostController@index")->setName('admin_post_index');
Router::get("/admin/articles/{id}", "Admin\PostController@show");
Router::all("/admin/nouvelle-article", "Admin\PostController@add");
// Router::all("/admin/post/edit/{id}/{csrfToken}", "Admin\PostController@edit")->where(['id' => '[0-9]+']);
Router::all("/admin/post/edit/{id}", "Admin\PostController@edit")->where(['id' => '[0-9]+']);
Router::all("/admin/post/delete/{id}/{csrfToken}", "Admin\PostController@delete")->where(['id' => '[0-9]+']);
// ------ COMMENTS -----
Router::all("/articles/{id}/commentaires", "Front\CommentController@index")->where(['id' => '[0-9]+']);
Router::all("/articles/{id}/commentaires/create", "Front\CommentController@create")->where(['id' => '[0-9]+']);
// // Affichage d'un article spécifique
Router::get("/posts/{id}", "PostController@show")->where(['id' => '[0-9]+']);

//------ ERRORS ------//
// $controllerNotFound = 'ErrorController@notFound';

// Router::get('/not-found', $controllerNotFound);
Router::get('/forbidden', 'ErrorController@forbidden');

// Router::error(function(Request $request,\Exception $exception) {
//     $codeException = $exception->getCode();
//     if($codeException === 404 ) {
//         response()->redirect('/not-found');
//     }
//     elseif($codeException === 403){
//         response()->redirect('/forbidden');
//     }
//     else{
//         $request->setRewriteCallback('ErrorController@notFound');
//     }

// });
