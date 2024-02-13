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

// ------ FRONT POSTS -----
Router::get("/articles", "Front\PostController@index");
// ------ ADMIN POSTS -----

// Router::get("/admin/posts", "Admin\PostController@index")->setName('admin_post_index');
// Router::get("/admin/posts/create", "Admin\PostController@create")->setName('admin_post_create');
// Router::post("/admin/posts", "Admin\PostController@store")->setName('admin_post_store');
// Router::get("/admin/posts/{id}/edit", "Admin\PostController@edit")->setName('admin_post_edit');
// Router::put("/admin/posts/{id}", "Admin\PostController@update")->setName('admin_post_update');
// Router::delete("/admin/posts/{id}", "Admin\PostController@destroy")->setName('admin_post_destroy');

Router::get("/admin/articles", "Admin\PostController@index")->setName('admin_post_index');
Router::get("/admin/posts/{id}", "Admin\PostController@show");
Router::all("/admin/nouvelle-article", "Admin\PostController@add");
// Router::all("/admin/post/edit/{id}/{csrfToken}", "Admin\PostController@edit")->where(['id' => '[0-9]+']);
Router::all("/admin/post/edit/{id}", "Admin\PostController@edit")->where(['id' => '[0-9]+']);
Router::all("/admin/post/delete/{id}/{csrfToken}", "Admin\PostController@delete")->where(['id' => '[0-9]+']);
// ------ COMMENTS -----
// Router::get("/", "HomepageController@homepage")->setName('home');


// // Affichage d'un article spécifique
Router::get("/posts/{id}", "PostController@show")->where(['id' => '[0-9]+']);
// Router::get("/posts/{id}", "PostController@show")->where(['id' => '[0-9]+'])->setName('posts.show');

// // Page de création d'un nouvel article
// Router::get("/posts/create", "PostController@create")->setName('posts.create');

// // Stockage d'un nouvel article
// Router::post("/posts", "PostController@store")->setName('posts.store');


// // Mise à jour d'un article existant
// Router::put("/posts/{id}", "PostController@update")->where(['id' => '[0-9]+'])->setName('posts.update');

// // Suppression d'un article
// Router::delete("/posts/{id}", "PostController@destroy")->where(['id' => '[0-9]+'])->setName('posts.destroy');







// Liste des articles
// Router::get("/posts", "PostController@index")->setName('posts.index');


// Router::all("/","PostController@");

//------ Posts -----//

// Router::get("/posts/{id}","PostController@show")->where(["id" => "[0-9]+"]);


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
