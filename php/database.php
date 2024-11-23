<?php

class Database {
    private string $file_folder_path = "../database/";

    private function getFileContent($relative_path): array {
        $path = $this->file_folder_path.$relative_path;
        print $path;
        $json_content = file_get_contents($path);
        $content_array = json_decode($json_content, true);
        return $content_array;
    }

    private function setFileContent(string $relative_path, array $content) {
        $path = $this->file_folder_path.$relative_path;
        $encoded_json = json_encode($content);
        file_put_contents($path, $encoded_json);
    }

    // CREATING MAIN DATA STRUCTURES

    public function addArticle(string $title, string $summary, string $body) {
        $articles = $this->getFileContent("articles.json");

        $id = uniqid("articleId", true);

        // TODO save header image

        $article_array = [
            "title"=>$title,
            "summary"=>$summary,
            "body"=>$body,
            "publish_date"=>date("d.m.Y"),
            "category"=>"",
            "comments"=>[]
        ];

        $articles[$id] = $article_array;

        $this->setFileContent("articles.json", $articles);
    }

    public function addUser(string $username, string $first_name, string $second_name, string $password) {
        $users = $this->getFileContent("users.json");

        $user_array = [
            "first_name"=>$title,
            "second_name"=>$summary,
            "password"=>$password, // TODO hashing
            "role"=>"default",
            "comments"=>[]
        ];

        $users[$username] = $user_array;

        $this->setFileContent("users.json", $users);
    }

    public function addComment(string $author, string $articleId, string $content) {
        $comments = $this->getFileContent("comments.json");

        $id = uniqid("commentId", true);

        $comment_array = [
            "author"=>$author,
            "articleId"=>$articleId,
            "content"=>$content,
            "publish_date"=>date("d.m.Y")
        ];

        $comments[$id] = $comment_array;

        $this->setFileContent("comments.json", $comments);
    }

    // EDITING DATA STRUCTURES

    public function editComment(string $commentId, string $newContent) {
        $comments = $this->getFileContent("comments.json");

        if (!isset($comments[$commentId])) return;
        $comments[$commentId]["content"] = $newContent;

        $this->setFileContent("comments.json", $comments);
    }

    public function changeUserRole(string $username, string $newRole) {
        $users = $this->getFileContent("users.json");

        if (!isset($users[$username])) return;
        $users[$username]["role"] = $newRole;

        $this->setFileContent("users.json", $users);
    }

    // DETELE DATA STRUCTURES

    public function removeArticle(string $articleId) {
        $articles = $this->getFileContent("articles.json");
        unset($articles[$articleId]);
        $this->setFileContent("articles.json", $articles);
    }

    public function removeComment(string $commentId) {
        $comments = $this->getFileContent("comments.json");
        unset($comments[$commentId]);
        $this->setFileContent("comments.json", $comments);
    }
}

?>