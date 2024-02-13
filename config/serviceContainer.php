<?php
// ```php
// class ServiceContainer {
//     private $commentRepository;
//     private $commentHandler;

//     public function getCommentRepository() {
//         if ($this->commentRepository === null) {
//             // Créez et configurez votre CommentRepository ici
//             $this->commentRepository = new CommentRepository();
//         }
//         return $this->commentRepository;
//     }

//     public function getCommentHandler() {
//         if ($this->commentHandler === null) {
//             $this->commentHandler = new CommentHandler($this->getCommentRepository());
//         }
//         return $this->commentHandler;
//     }
// }
// ```
