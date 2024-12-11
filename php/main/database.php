<?php

require_once "../tools/resize_image.php";

class User {
    public function __construct(string $username, string $first_name, string $second_name, string $password, string $role, array $comments) {
        $this->username = $username;
        $this->first_name = $first_name;
        $this->second_name = $second_name;
        $this->password = $password;
        $this->role = $role;
        $this->comments = $comments;
    }

    public function isAdmin() {
        return $this->role == "admin";
    }

    public function isWriter() {
        return $this->role == "writer";
    }
}

class Comment {
    public function __construct(string $id, string $author, string $articleId, string $content, string $publish_date) {
        $this->id = $id;
        $this->author = $author;
        $this->articleId = $articleId;
        $this->content = $content;
        $this->publish_date = $publish_date;
    }
}

class Article {
    public function __construct(
        string $id, 
        string $title, 
        string $summary, 
        string $body, 
        string $image_path,
        string $category, 
        string $publish_date, 
        array $comments
    ) {
        $this->id = $id;
        $this->title = $title;
        $this->summary = $summary;
        $this->body = $body;
        $this->image_path = $image_path;
        $this->category = $category;
        $this->publish_date = $publish_date;
        $this->comments = $comments;
    }
}

class Database {
    private string $file_folder_path = __DIR__."/../../database/";

    private function getFileContent($relative_path): array {
        $path = $this->file_folder_path.$relative_path;
        $json_content = file_get_contents($path);
        $content_array = json_decode($json_content, true);
        return $content_array;
    }

    private function setFileContent(string $relative_path, array $content) {
        $path = $this->file_folder_path.$relative_path;
        $encoded_json = json_encode($content);
        file_put_contents($path, $encoded_json);
    }

    private function buildArticleObject(string $id, array $data): Article {
        return new Article(
            $id,
            $data["title"],
            $data["summary"],
            $data["body"],
            $data["image_path"],
            $data["category"],
            $data["publish_date"],
            $data["comments"]
        );
    }

    private function buildUserObject(string $username, array $data): User {
        return new User(
            $username,
            $data["first_name"],
            $data["second_name"],
            $data["password"],
            $data["role"],
            $data["comments"]
        );
    }

    private function buildCommentObject($id, $data): Comment {
        return new Comment(
            $id,
            $data["author"],
            $data["articleId"],
            $data["content"],
            $data["publish_date"]
        );
    }

    // IMAGES
    public function saveImage(array $image_data): string | NULL {
        $new_name = uniqid("headerImg").$image_data["name"];
        foreach(["small", "medium", "large"] as $size_type) {
            $target_path = $this->file_folder_path."images/".$size_type."/".$new_name;
            resize_image_to_type($image_data["tmp_name"], $size_type, $target_path);
        }
        return $new_name;
    }

    // CREATING MAIN DATA STRUCTURES

    public function addArticle(string $title, string $summary, string $body, string $image_path, string $category): string {
        $articles = $this->getFileContent("articles.json");

        $id = uniqid("articleId", true);

        $article_array = [
            "title"=>$title,
            "summary"=>$summary,
            "body"=>$body,
            "image_path"=>$image_path,
            "publish_date"=>date("d.m.Y"),
            "category"=>$category,
            "comments"=>[]
        ];

        $articles[$id] = $article_array;

        $this->setFileContent("articles.json", $articles);

        return $id;
    }

    public function addUser(string $username, string $first_name, string $second_name, string $password) {
        $users = $this->getFileContent("users.json");

        $user_array = [
            "first_name"=>$first_name,
            "second_name"=>$second_name,
            "password"=>password_hash($password, PASSWORD_DEFAULT),
            "role"=>"user",
            "comments"=>array()
        ];

        $users[$username] = $user_array;

        $this->setFileContent("users.json", $users);
    }

    public function addComment(string $author, string $articleId, string $content): string {
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

        // link comment to article
        $articles = $this->getFileContent("articles.json");
        array_push($articles[$articleId]["comments"], $id);
        $this->setFileContent("articles.json", $articles);

        // link comment to user
        $users = $this->getFileContent("users.json");
        array_push($users[$author]["comments"], $id);
        $this->setFileContent("users.json", $users);

        return $id;
    }

    // EDITING DATA STRUCTURES

    public function editComment(string $commentId, string $new_content) {
        $comments = $this->getFileContent("comments.json");

        if (!isset($comments[$commentId])) return;
        $comments[$commentId]["content"] = $new_content;

        $this->setFileContent("comments.json", $comments);
    }

    public function changeUserRole(string $username, string $newRole) {
        $users = $this->getFileContent("users.json");

        if (!isset($users[$username])) return;
        $users[$username]["role"] = $newRole;

        $this->setFileContent("users.json", $users);
    }

    public function changePassword(string $username, string $new_password) {
        $users = $this->getFileContent("users.json");

        if (!isset($users[$username])) return;
        $users[$username]["password"] = $new_password; // TODO hash pasword

        $this->setFileContent("users.json", $users);
    }

    // DETELE DATA STRUCTURES

    public function removeArticle(Article $article) {
        $articles = $this->getFileContent("articles.json");

        if(!isset($articles[$article->id])) return;
        foreach(["large", "medium", "small"] as $size_type) {
            $path = $this->file_folder_path."/images/".$size_type."/".$articles[$article->id]["image_path"];
            if(file_exists($path)) unlink($path);
        }

        // delete comments and unlink from user
        $comments = $this->getFileContent("comments.json");
        $users = $this->getFileContent("users.json");
        foreach($article->comments as $comment_id) {
            $author_username = $comments[$comment_id]["author"];
            $i = array_search($comment_id, $users[$author_username]["comments"]);
            unset($users[$author_username]["comments"][$i]);
            unset($comments[array_search($comment_id, $comments)]);
        }
        $this->setFileContent("comments.json", $comments);
        $this->setFileContent("users.json", $users);

        unset($articles[$article->id]);
        $this->setFileContent("articles.json", $articles);
    }

    public function removeComment(Comment $comment) {
        $articles = $this->getFileContent("articles.json");
        $users = $this->getFileContent("users.json");

        $i = array_search($comment_id, $articles[$comment->articleId]["comments"]);
        unset($articles[$comment->articleId]["comments"][$comment->id]);

        $i = array_search($comment_id, $users[$comment->author]["comments"]);
        unset($users[$comment->author]["comments"][$comment->id]);

        $this->setFileContent("articles.json", $articles);
        $this->setFileContent("users.json", $users);

        $comments = $this->getFileContent("comments.json");
        unset($comments[$comment->id]);
        $this->setFileContent("comments.json", $comments);
    }

    // INFORMATIVE FUNCTIONS

    public function userExists(string $username): bool {
        $users = $this->getFileContent("users.json");
        if (isset($users[$username])) return true;
        return false;
    }

    public function articleExists(string $id): bool {
        $articles = $this->getFileContent("articles.json");
        if (isset($articles[$id])) return true;
        return false;
    }

    // GET FUNCTIONS

    public function getUser(string $username): User | null {
        $users = $this->getFileContent("users.json");
        if (!isset($users[$username])) return null;
        return $this->buildUserObject($username, $users[$username]);
    }

    public function getUsers(): array {
        $usersFile = $this->getFileContent("users.json");
        $users = array();
        foreach($usersFile as $username => $data) {
            $users[] = $this->buildUserObject($username, $data);
        }
        return $users;
    }

    public function getCommentById(string $comment_id): Comment {
        $comments = $this->getFileContent("comments.json");
        $comment = $this->buildCommentObject($comment_id, $comments[$comment_id]);
        return $comment;
    }

    public function getCommentsFromIds(array $commentIds): array {
        $commentsFile = $this->getFileContent("comments.json");

        $comments = array();
        foreach($commentIds as $qId) {
            if(isset($commentsFile[$qId])) {
                $comments[] = $this->buildCommentObject($qId, $commentsFile[$qId]);
            }
        }

        return $comments;
    }

    public function getArticle(string $id): Article | null {
        $articles = $this->getFileContent("articles.json");
        if(!isset($articles[$id])) return null;
        
        return $this->buildArticleObject($id, $articles[$id]);
    }

    public function getArticlesOfCategory(string $category): array {
        $matching_articles = [];
        $articles = $this->getFileContent("articles.json");
        foreach($articles as $id => $data) {
            if ($data["category"] == $category) $matching_articles[] = $this->buildArticleObject($id, $data);
        }
        return $matching_articles;
    }

    public function getMaxGroupsOfArticles(int $size): int {
        $articles = $this->getFileContent("articles.json");
        $amount = ceil(sizeof($articles) / $size);
        return $amount;
    }

    public function getGroupOfArticles(int $groupNumber, int $size): array {
        $articles = $this->getFileContent("articles.json");
        $reversed_articles = array_reverse($articles, true);
        $groups = array_chunk($reversed_articles, $size, true);
        if(sizeof($groups) == 0) return [];
        $target_group = $groups[$groupNumber-1];
        $group_with_objects = [];
        foreach($target_group as $id => $data) {
            $group_with_objects[] = $this->buildArticleObject($id, $data);
        }

        return $group_with_objects;
    }
}

?>