<?php

use Core\Router;
use Pecee\Http\Request;

Router::setDefaultNamespace("App\Controller");
// ------ ADMIN -----
Router::get("/admin/articles", "Admin\PostController@index")->setName('admin_post_index');
Router::get("/admin/posts/{id}", "Admin\PostController@showPost");
Router::all("/admin/nouvelle-article", "Admin\PostController@addPost");
Router::all("/admin/post/edit/{id}", "Admin\PostController@edit")->where(['id' => '[0-9]+'])->setName('posts.edit');
Router::all("/admin/post/delete/{id}/{csrfToken}", "Admin\PostController@delete")->where(['id' => '[0-9]+'])->setName('posts.edit');
//------ HOME -----//
Router::get("/", "HomepageController@homepage");

// --- Athentication
Router::all("/inscription", "admin\RegisterController@register");


// Router::get("/", "HomepageController@homepage")->setName('home');


// // Liste des articles
// Router::get("/posts", "PostController@index")->setName('posts.index');

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
// Router::get('/forbidden', $controllerNotFound);


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
