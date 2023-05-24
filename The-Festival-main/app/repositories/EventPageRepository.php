<?php
require_once __DIR__ . '/../Models/EventPage.php';
require_once __DIR__ . '/../Models/Content.php';
require_once __DIR__ . '/../Models/SectionText.php';
require_once __DIR__ . '/../Models/BodyHead.php';
require_once __DIR__ . '/repository.php';

class EventPageRepository extends Repository
{
    public function getEventPageByName($name)
    {
        try {
            $stmt = $this->connection->prepare("SELECT eventPageId,eventPageName,content,url,image,ticket  FROM eventpage WHERE eventPageName = :name");
            $stmt->bindParam(':name', $name);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            if($stmt->rowCount() > 0){
                return $this->createEventPageInstance($result);
            }
            return null;
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    private function createEventPageInstance($result) : EventPage
    {
        try{
            $eventPage = new EventPage();
            $eventPage->setEventPageId($result['eventPageId']);
            $eventPage->setEventPageName($result['eventPageName']);
            $content = $this->getContent($result['content']); // Getting Content Object
            $eventPage->setContent($content);
            $eventPage->setUrl($result['url']);
            $eventPage->setImage($result['image']);
            $eventPage->setTicket($result['ticket']);
            return $eventPage;
        }
        catch (Exception $e) {
            echo $e->getMessage();
        }
    }


    private function getContent($contentId)
    {
        try {
            // reading content table and making content object
            $stmt = $this->connection->prepare("SELECT contentId, bodyHead,h2,h3,p FROM content WHERE contentId = :contentId");
            $stmt->bindParam(':contentId', $contentId);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($stmt->rowCount() == 0){
                return null;
            }
            return $this->createContentInstance($result);


        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    private function createContentInstance($result): Content
    { // create content object by reading from database
        try{
            $content = new Content();
            $content->setContentId($result['contentId']);
            $content->setH2($result['h2']);
            $bodyHead = $this->getBodyHead($result['bodyHead']); // getting object Body Head by Reading from database
            $content->setBodyHead($bodyHead);
            $sectionText = $this->getSectionText($result['contentId']); // getting object Section Text by Reading from database
            $content->setSectionText($sectionText);
            return $content;
        } catch (Exception $e) {
            echo $e->getMessage();
        }

    }

    private function getBodyHead($bodyHeadId)
    {
        try {
            // reading bodyhead table and making bodyhead object
            $stmt = $this->connection->prepare("SELECT bodyHeadId,h1,h2,image FROM bodyhead WHERE bodyHeadId = :bodyHeadId");
            $stmt->bindParam(':bodyHeadId', $bodyHeadId);
            $stmt->execute();
            $stmt->setFetchMode(PDO::FETCH_CLASS, 'BodyHead');
            return $stmt->fetch();
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    private function getSectionText($contentId)
    {
        try {
            $stmt = $this->connection->prepare("SELECT paragraph ,contentId FROM sectiontext WHERE contentId = :contentId");
            $stmt->bindParam(':contentId', $contentId);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($stmt->rowCount() == 0){
                return null;
            }
            return $this->createSectionTextInstance($result);
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    private function createSectionTextInstance($result): SectionText
    {
        $sectionText = new SectionText();
        $contentId = $result['contentId'];
        $paragraphs = $this->getParagraphsByContentId($contentId);
        $sectionText->setParagraphs($paragraphs);
        return $sectionText;
    }

    private function getParagraphsByContentId($contentId)
    {
        // reading paragraph table and making paragraph object by Content ID
        try {
            $stmt = $this->connection->prepare("SELECT  paragraph.paragraphId,  paragraph.title , paragraph.text  FROM sectiontext JOIN paragraph ON paragraph.paragraphId = sectiontext.paragraph WHERE sectiontext.contentId = :contentId");
            $stmt->bindParam(':contentId', $contentId);
            $stmt->execute();
            if ($stmt->rowCount() == 0){
                return null;
            }
            $result= $stmt->fetchAll(PDO::FETCH_ASSOC);
            $paragraphs = array();
            foreach ($result as $row){
               $paragraphs[]=new Paragraph($row['paragraphId'],$row['title'],$row['text']); // just made a paragraph constructor
            }
            return $paragraphs;
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }


    public function getParagraphByEventId($eventId){
        try {
            $stmt = $this->connection->prepare("SELECT paragraph.title, paragraph.text 
                                                    FROM paragraph 
                                                    JOIN eventparagraph ON paragraph.paragraphId = eventparagraph.paragraphId
                                                    WHERE eventId = :eventId;");
            $stmt->bindParam(':eventId', $eventId);
            $stmt->execute();
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $paragraphs = array();
            foreach ($results as $result) {
                $paragraphs[] = $this->createParagraph($result);
            }

            return $paragraphs;

        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }
    private function createParagraph($result): Paragraph
    { // create content object by reading from database
        try{
            $paragraph = new Paragraph();
            $paragraph->setTitle($result['title']);
            $paragraph->setText($result['text']);

            return $paragraph;
        } catch (Exception $e) {
            echo $e->getMessage();
        }

    }
}