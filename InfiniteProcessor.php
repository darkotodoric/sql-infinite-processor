<?php

class InfiniteProcessor
{
    /** @var PDO */
    private PDO $db;

    /** @var int */
    private int $lastId = 0;

    public function __construct()
    {
        $this->db = new PDO('mysql:host=127.0.0.1;port=3306;dbname=someDbName', 'root', '123456');
    }

    public function process()
    {
        while(true){
            $items = $this->getItems();
            if(empty($items)){
                break;
            }

            foreach($items as $item){
                // do something
            }
        }
    }

    /**
     * @return array
     */
    private function getItems(): array
    {
        $stmt = $this->db->prepare('
            SELECT id, title, url
            FROM items
            WHERE id > :lastId
            ORDER BY id ASC
            LIMIT 1000
            ');
        $stmt->bindParam(':lastId', $this->lastId);
        $stmt->execute();

        $data = [];
        while($row = $stmt->fetch(\PDO::FETCH_ASSOC)){
            $this->lastId = $row['id'];
            $data[] = $row;
        }

        return $data;
    }
}
