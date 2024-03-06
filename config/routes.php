<?php

use Core\Router;

Router::setDefaultNamespace("App\Controller");

// ----- HOME -----
Router::get("/homepage", "HomepageController@homepage");

//------ Legal infos -----
Router::get("/legal-infos", "LegalInfoController@showLegalInfoPage");

// ----- AUTHENTICATION -----
Router::all("/inscription", "RegisterController@register")->setName('inscription');
Router::all("/inscription-confirmation/{token}", "RegisterController@validationToken");

Router::all("/connexion", "RegisterController@login");
Router::all("/deconnexion", "RegisterController@logout");

Router::all("/mot-de-passe-oublie", "RegisterController@forgotPassword");
Router::all("/mot-de-passe-oublie/{token}", "RegisterController@validationNewPassword");

// ----- CONTACT EMAIL -----
Router::post("/send-email-contact", "MailerController@sendContentContactForm");

// ----- FRONT POSTS -----
Router::get("/articles", "Front\PostController@index");
Router::get("/articles/{id}", "Front\PostController@show")->where(['id' => '[0-9]+']);

// -----  FRONT COMMENTS -----
Router::all("/articles/{id}/commentaires", "Front\CommentController@index")->where(['id' => '[0-9]+']);
Router::all("/articles/{id}/commentaires/create", "Front\CommentController@create")->where(['id' => '[0-9]+']);
Router::get("/posts/{id}", "PostController@show")->where(['id' => '[0-9]+']);

// ----- ADMIN COMMENTS -----
Router::get("/admin/commentaires", "Admin\CommentController@index");
Router::all("/admin/commentaire/{id}/{status}/{csrfToken}", "Admin\CommentController@changeStatus");

// ----- ADMIN POSTS -----
Router::get("/admin/articles", "Admin\PostController@index")->setName('admin_post_index');
Router::get("/admin/articles/{id}", "Admin\PostController@show")->where(['id' => '[0-9]+']);
Router::all("/admin/nouvelle-article", "Admin\PostController@add");
Router::all("/admin/post/edit/{id}", "Admin\PostController@edit")->where(['id' => '[0-9]+']);
Router::all("/admin/post/delete/{id}/{csrfToken}", "Admin\PostController@delete")->where(['id' => '[0-9]+']);

// ----- ERRORS -----
Router::get('/not-found', "ErrorController@notFound");
Router::get('/error', "ErrorController@error");
Router::get('/forbidden', 'ErrorController@forbidden');

Router::get("/test-error", function() {
  throw new \Exception("Ceci est une erreur de test.");
});
