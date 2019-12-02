<?php
class PostsController extends AppController {
    public $helpers = array('Html', 'Form');

    public function index() {
         $this->set('posts', $this->Post->find('all', array("conditions" => array("Post.created <=" => date("Y-m-d H:i:s")), "order" => array("Post.created" => "DESC"))));
         $this->set("page_name", "blog_index");
    }

    public function view($title = null) {
        if (!$title) {
            throw new NotFoundException(__('Invalid post'));
        }

        $post = $this->Post->findByTitle($title);
        if (!$post) {
            throw new NotFoundException(__('Invalid post'));
        }
        $this->set('post', $post);
    		$this->set("page_name", "blog_view");
    }

}
