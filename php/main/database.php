<?php

/**
 * Databáze pro server PulseWire.
 * @author Robin Škába
 */

require_once "../tools/resize_image.php";

/**
 * Třída reprezentující jednotlivého uživatele.
 * Je návratovou hodnotou mnoha funkcí databáze, aby ostatní php soubory nemusely znát strukturu uživatele v databázi.
 */
class User {
    /**
     * Konstruktor pro třídu User.
     * @param string $username Uživatelské jméno uživatele
     * @param string $first_name Křestní jméno uživatele
     * @param string $second_name Příjmení uživatele
     * @param string $password Zahashované heslo uživatele
     * @param string $role Role uživatele
     * @param string $comments Pole id komentářů, které uživatel napsal
     */
    public function __construct(string $username, string $first_name, string $second_name, string $password, string $role, array $comments) {
        $this->username = $username;
        $this->first_name = $first_name;
        $this->second_name = $second_name;
        $this->password = $password;
        $this->role = $role;
        $this->comments = $comments;
    }

    /**
     * Funkce, která zjistí, zda je uživatel administrátor.
     * @return bool True, pokud je uživatel administrátor, jinak false.
     */
    public function isAdmin() {
        return $this->role == "admin";
    }

    /**
     * Funkce, která zjistí, zda uživatel smí psát články.
     * @return bool True, pokud je uživatel "redaktor", jinak false.
     */
    public function isWriter() {
        return $this->role == "writer";
    }
}

/**
 * Třída reprezentující jednotlivý komentář.
 * Je návratovou hodnotou mnoha funkcí databáze, aby ostatní php soubory nemusely znát strukturu komentáře v databázi.
 */
class Comment {

    /**
     * Konstruktor pro třídu Comment.
     * @param string $id ID komentáře
     * @param string $author Autor komentáře (uživatelské jméno)
     * @param string $articleId ID článku, ke kterému komentář patří
     * @param string $content Obsah komentáře
     * @param string $publish_date Datum publikace komentáře
     */
    public function __construct(string $id, string $author, string $articleId, string $content, string $publish_date) {
        $this->id = $id;
        $this->author = $author;
        $this->articleId = $articleId;
        $this->content = $content;
        $this->publish_date = $publish_date;
    }
}

/**
 * Třída reprezentující jednotlivý článek.
 * Je návratovou hodnotou mnoha funkcí databáze, aby ostatní php soubory nemusely znát strukturu článku v databázi.
 */
class Article {

    /**
     * Konstruktor pro třídu Article.
     * @param string $id ID článku
     * @param string $title Titulek článku
     * @param string $summary Stručný popis článku
     * @param string $body Obsah článku
     * @param string $image_path Cesta k obrázku článku
     * @param string $category Kategorie, do které článek patří
     * @param string $publish_date Datum publikace článku
     * @param array $comments Pole id komentářů, které byly napsány pod článkem
     */
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

/**
 * Třída reprezentující databázi.
 * Je volaná z ostatních php souborů, aby mohly pracovat s daty v databázi.
 */
class Database {

    /**
     * @var string Cesta ke složce, ve které jsou uloženy různé datové soubory.
     */
    private string $file_folder_path = __DIR__."/../../database/";

    /**
     * Funkce, která načte obsah souboru a vrátí ho jako pole.
     * @param string $relative_path Relativní cesta k souboru - je přidána k cestě hlavní složky databáze
     * @return array Obsah souboru
     */
    private function getFileContent(string $relative_path): array {
        $path = $this->file_folder_path.$relative_path;
        $json_content = file_get_contents($path);
        $content_array = json_decode($json_content, true);
        return $content_array;
    }

    /**
     * Funkce, která uloží pole do souboru.
     * @param string $relative_path Relativní cesta k souboru - je přidána k cestě hlavní složky databáze
     * @param array $content Pole, které má být uloženo do souboru
     */
    private function setFileContent(string $relative_path, array $content) {
        $path = $this->file_folder_path.$relative_path;
        $encoded_json = json_encode($content);
        file_put_contents($path, $encoded_json);
    }

    /**
     * Vytvoří objekt článku z jeho id a dat.
     * Slouží k ulehčení tvoření objektu pro návratovou hodnotu funkcí databáze.
     * @param string $id ID článku
     * @param array $data Data článku v databázi
     * @return Article Objekt článku
     */
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

    /**
     * Vytvoří objekt uživatele z jeho uživatelského jména a dat.
     * Slouží k ulehčení tvoření objektu pro návratovou hodnotu funkcí databáze.
     * @param string $username Uživatelské jméno uživatele
     * @param array $data Data uživatele v databázi
     * @return User Objekt uživatele
     */
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

    /**
     * Vytvoří objekt komentáře z jeho id a dat.
     * Slouží k ulehčení tvoření objektu pro návratovou hodnotu funkcí databáze.
     * @param string $id ID komentáře
     * @param string $data Data komentáře v databázi
     * @return Comment Objekt komentáře
     */
    private function buildCommentObject($id, $data): Comment {
        return new Comment(
            $id,
            $data["author"],
            $data["articleId"],
            $data["content"],
            $data["publish_date"]
        );
    }

    /**
     * Uloží obrázek do složky images a vytvoří jeho kopie ve třech různých velikostech.
     * @param $image_data Data obrázku z $_FILES
     * @return string|null Název souboru, pokud se obrázek podařilo uložit, jinak null
     */
    public function saveImage(array $image_data): string | NULL {
        $new_name = uniqid("headerImg").str_replace(' ', '', $image_data["name"]);
        foreach(["small", "medium", "large"] as $size_type) {
            $target_path = $this->file_folder_path."images/".$size_type."/".$new_name;
            resize_image_to_type($image_data["tmp_name"], $size_type, $target_path);
        }
        return $new_name;
    }

    /**
     * Přidá nový článek do databáze, vytvoří mu unikátní id a uloží ho do souboru.
     * @param string $title Titulek článku
     * @param string $summary Stručný popis článku
     * @param string $body Obsah článku
     * @param string $image_path Cesta k obrázku článku - pouze název, obrázek je pod stejným názvem uložen v třech různých velikostech na třech různých místech
     * @param string $category Kategorie, do které článek patří
     * @return string ID nového článku
     */
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

    /**
     * Přidá nového uživatele do databáze, vytvoří mu unikátní id a uloží ho do souboru.
     * @param string $username Uživatelské jméno uživatele
     * @param string $first_name Křestní jméno uživatele
     * @param string $second_name Příjmení uživatele
     * @param string $password Heslo uživatele
     */
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

    /**
     * Přidá nový komentář do databáze, vytvoří mu unikátní id a uloží ho do souboru.
     * Navíc propojí komentář s uživatelem a článkem.
     * @param string $author Autor komentáře (uživatelské jméno)
     * @param string $articleId ID článku, ke kterému komentář patří
     * @param string $content Obsah komentáře
     * @return string ID nového komentáře
     */
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

    /**
     * Upraví obsah komentáře v databázi.
     * @param string $commentId ID komentáře, který má být upraven
     * @param string $new_content Nový obsah komentáře
     */
    public function editComment(string $commentId, string $new_content) {
        $comments = $this->getFileContent("comments.json");

        if (!isset($comments[$commentId])) return;
        $comments[$commentId]["content"] = $new_content;

        $this->setFileContent("comments.json", $comments);
    }

    /**
     * Změní roli uživatele v databázi.
     * @param string $username Uživatelské jméno uživatele, jehož role má být změněna
     * @param string $newRole Nová role uživatele
     */
    public function changeUserRole(string $username, string $newRole) {
        $users = $this->getFileContent("users.json");

        if (!isset($users[$username])) return;
        $users[$username]["role"] = $newRole;

        $this->setFileContent("users.json", $users);
    }

    /**
     * Změní heslo uživatele v databázi - uloží hash hesla.
     * @param string $username Uživatelské jméno uživatele, jehož heslo má být změněno
     * @param string $new_password Nové heslo uživatele (nezhashovaná podoba)
     */
    public function changePassword(string $username, string $new_password) {
        $users = $this->getFileContent("users.json");

        if (!isset($users[$username])) return;
        $users[$username]["password"] = password_hash($new_password, PASSWORD_DEFAULT);

        $this->setFileContent("users.json", $users);
    }

    /**
     * Odstraní článek z databáze a všechny jeho komentáře.
     * @param Article $article Článek, který má být odstraněn
     */
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
            unset($comments[$comment_id]);
        }
        $this->setFileContent("comments.json", $comments);
        $this->setFileContent("users.json", $users);

        unset($articles[$article->id]);
        $this->setFileContent("articles.json", $articles);
    }

    /**
     * Odstraní komentář z databáze a odstraní jeho propojení s uživatelem a článkem.
     * @param Comment $comment Komentář, který má být odstraněn
     */
    public function removeComment(Comment $comment) {
        $articles = $this->getFileContent("articles.json");
        $users = $this->getFileContent("users.json");

        $i = array_search($comment->id, $articles[$comment->articleId]["comments"]);
        unset($articles[$comment->articleId]["comments"][$i]);

        $i = array_search($comment->id, $users[$comment->author]["comments"]);
        unset($users[$comment->author]["comments"][$i]);

        $this->setFileContent("articles.json", $articles);
        $this->setFileContent("users.json", $users);

        $comments = $this->getFileContent("comments.json");
        unset($comments[$comment->id]);
        $this->setFileContent("comments.json", $comments);
    }

    /**
     * Odstraní uživatele z databáze a odstraní všechny jeho komentáře.
     * @param User $user Uživatel, který má být odstraněn
     */
    public function removeUser(User $user) {
        $users = $this->getFileContent("users.json");

        $comments = $this->getFileContent("comments.json");
        $articles = $this->getFileContent("articles.json");
        
        if(!isset($users[$user->username])) return;

        // delete comments and unlink from article
        foreach($user->comments as $comment_id) {
            $comment = $comments[$comment_id];

            $articleId = $comment["articleId"];
            $i = array_search($comment_id, $articles[$articleId]["comments"]);
            unset($articles[$articleId]["comments"][$i]);

            unset($comments[$comment_id]);
        }

        $this->setFileContent("comments.json", $comments);
        $this->setFileContent("articles.json", $articles);

        unset($users[$user->username]);
        $this->setFileContent("users.json", $users);
    }

    /**
     * Zjistí, zda uživatel s daným uživatelským jménem existuje.
     * @param string $username Uživatelské jméno uživatele
     * @return bool True, pokud uživatel existuje, jinak false
     */
    public function userExists(string $username): bool {
        $users = $this->getFileContent("users.json");
        if (isset($users[$username])) return true;
        return false;
    }

    /**
     * Zjistí, zda článek s daným id existuje.
     * @param string $id ID článku
     * @return bool True, pokud článek existuje, jinak false
     */
    public function articleExists(string $id): bool {
        $articles = $this->getFileContent("articles.json");
        if (isset($articles[$id])) return true;
        return false;
    }

    /**
     * Vrátí objekt uživatele podle jeho uživatelského jména.
     * @param string $username Uživatelské jméno uživatele
     * @return User|null Objekt uživatele, pokud existuje, jinak null
     */
    public function getUser(string $username): User | null {
        $users = $this->getFileContent("users.json");
        if (!isset($users[$username])) return null;
        return $this->buildUserObject($username, $users[$username]);
    }

    /**
     * Vrátí všechny uživatele v databázi.
     * @return array Pole objektů uživatelů
     */
    public function getUsers(): array {
        $usersFile = $this->getFileContent("users.json");
        $users = array();
        foreach($usersFile as $username => $data) {
            $users[] = $this->buildUserObject($username, $data);
        }
        return $users;
    }

    /**
     * Najde komentář podle jeho id a vrátí jeho objekt z databáze.
     * @param string $comment_id ID komentáře
     * @return Commet Objekt komentáře
     */
    public function getCommentById(string $comment_id): Comment {
        $comments = $this->getFileContent("comments.json");
        $comment = $this->buildCommentObject($comment_id, $comments[$comment_id]);
        return $comment;
    }

    /**
     * Vrátí více komentářů podle žádaných ID komentářů.
     * @param array $commentIds Pole ID komentářů
     * @return array Pole objektů komentářů
     */
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

    /**
     * Vrátí objekt článku podle hledaného ID.
     * @param string $id ID článku
     * @return Article|null Objekt článku, pokud existuje, jinak null
     */
    public function getArticle(string $id): Article | null {
        $articles = $this->getFileContent("articles.json");
        if(!isset($articles[$id])) return null;
        
        return $this->buildArticleObject($id, $articles[$id]);
    }

    /**
     * Vrátí všechny články dané kategorie.
     * @param string $category Kategorie, podle které se mají články filtrovat
     * @return array Pole objektů článků
     */
    public function getArticlesOfCategory(string $category): array {
        $matching_articles = [];
        $articles = $this->getFileContent("articles.json");
        foreach($articles as $id => $data) {
            if ($data["category"] == $category) $matching_articles[] = $this->buildArticleObject($id, $data);
        }
        return $matching_articles;
    }

    /**
     * Vrátí maximální počet skupin dané velikosti, které lze vytvořit z článků v databází.
     * @param int $size Velikost skupiny článků
     * @return int Počet skupin
     */
    public function getMaxGroupsOfArticles(int $size): int {
        $articles = $this->getFileContent("articles.json");
        $amount = ceil(sizeof($articles) / $size);
        return $amount;
    }

    /**
     * Vrátí skupinu článků podle čísla skupiny a její velikosti.
     * Skupina se odvíjí od pořadí článků v databázi, které odpovídá opačnému pořadí jejich publikace, a tím vypočítá skupinu.
     * Slouží pro stránkování článků na domovské stránce.
     * @param int $groupNumber Pořadové číslo skupiny
     * @param int $size Velikost skupiny
     * @return array Pole objektů článků
     */
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