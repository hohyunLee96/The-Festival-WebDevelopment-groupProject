<?php
require_once __DIR__ . '/repository.php';
require_once __DIR__ . '/../models/DanceEvent/artist.php';
require_once __DIR__ . '/../models/DanceEvent/Style.php';

class ArtistRepository extends Repository
{
    public function getAllArtists(): ?array
    {
        $query = "SELECT artist.artistId, artist.artistName, artist.artistDescription, image.imageName FROM artist 
            JOIN image ON artist.artistLogoId = image.imageId";
        $result = $this->executeQuery($query);
        $artists = array();
        if (empty($result)) {
            return null;
        }
        foreach ($result as $artistRow) {
            $artists[] = $this->createArtistInstance($artistRow);
        }
        return $artists;
    }

    public function getAllArtistsParticipatingInEvent(): ?array
    {
        $query = "SELECT participatingartist.artistId,artist.artistDescription, artist.artistName, image.imageName
                FROM participatingartist
                JOIN artist ON participatingartist.artistId = artist.artistId
                JOIN image ON artist.artistLogoId = image.imageId
                GROUP BY participatingartist.artistId"; // gives all the artist participating in the event
        $result = $this->executeQuery($query);
        if (empty($result)) {
            return null;
        }
        $artists = array();
        foreach ($result as $artistRow) {
            $artists[] = $this->createArtistInstance($artistRow);
        }
        return $artists;
    }

    private function createArtistInstance($row): Artist
    {
        try {
            $artist = new Artist();
            $artist->setArtistId($row['artistId']);
            $artist->setArtistName($row['artistName']);
            $artist->setArtistDescription($row['artistDescription']);
            $artist->setArtistLogo($row['imageName']);
            $artist->setArtistImages($this->getAllImagesOfArtistByArtistId($row['artistId']));
            $artist->setArtistStyles($this->getArtistStylesByArtistID($row['artistId'])); // getting artist styles
            return $artist;
        } catch (Exception $e) {
            echo "Error while creating user instance: " . $e->getMessage();
        }
    }

    public function getAllImagesOfArtistByArtistId($artistId): ?array
    {
        $query = "SELECT image.imageName,artistImage.Imagespecification as imageSpecification  
        From artistImage JOIN image ON artistImage.imageId = image.imageId WHERE artistImage.artistId = :artistId";
        $result = $this->executeQuery($query, array(':artistId' => $artistId));
        if (!empty($result)) {
            return $this->getImagesWithKeyValue($result);
        }
        return null;
    }

    private function getImagesWithKeyValue($result): array
    {
        $images = array();
        foreach ($result as $imageRow) {
            $imageName = $imageRow['imageName'];
            $imageSpec = $imageRow['imageSpecification'];
            if (isset($images[$imageSpec])) { // storing images as key value pair in array
                $images[$imageSpec][] = $imageName;
            } else {
                $images[$imageSpec] = array($imageName);
            }
        }
        return $images;
    }

    public function getArtistByName($name)
    {
        $query = "SELECT artist.artistId, artist.artistName, artist.artistDescription, image.imageName FROM artist JOIN image ON artist.artistLogoId = image.imageId WHERE artistName = :name";
        $result = $this->executeQuery($query, array(':name' => $name), false);
        if (!empty($result)) {
            return $this->createArtistInstance($result);
        }
        return $result; // it is going to be null
    }

    public function getArtistByArtistID($artistID)
    {
        $query = "SELECT artist.artistId, artist.artistName, artist.artistDescription, image.imageName FROM artist JOIN image ON artist.artistLogoId = image.imageId WHERE artistId = :artistID";
        $result = $this->executeQuery($query, array(':artistID' => $artistID), false);
        if (!empty($result)) { // preventing null pointer exception
            return $this->createArtistInstance($result);
        }
        return $result;
    }

    public function getArtistStylesByArtistID($artistId)
    {
        $query = "SELECT style.styleId,style.styleName FROM artistStyle JOIN style ON artistStyle.styleId = style.styleId WHERE artistStyle.artistId = :artistID";
        $result = $this->executeQuery($query, array(':artistID' => $artistId));
        $styles = array();
        if (!empty($result)) {
            foreach ($result as $row) {
                $styles[] = new Style($row['styleId'], $row['styleName']);
            }
            return $styles;
        }
        return null;
    }

    public function deleteArtistById($artistId)
    {
        $stmt = $this->connection->prepare("DELETE FROM `artist` WHERE artistId = :artistId");
        $stmt->bindParam(':aristId', $artistId);
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $result = $stmt->fetch();

    }

    public function getAllParticipatingArtistsInPerformance($performanceId): ?array
    {
        $query = "SELECT artistId FROM participatingArtist WHERE PerformanceId = :PerformanceId";
        $result = $this->executeQuery($query, array(':PerformanceId' => $performanceId));
        if (empty($result)) {
            return null;
        }
        $artists = array();
        foreach ($result as $row) {
            $artists[] = $this->getArtistByArtistID($row['artistId']);
        }
        return $artists;
    }

    public function getArtistNameByArtistId($id)
    {
        try {
            $stmt = $this->connection->prepare("SELECT artistName FROM artist WHERE artistId=:artistId");
            $stmt->bindParam(':artistId', $id);
            $stmt->execute();
            $result = $stmt->fetch();
            if ($result != 0) {
                return current($result);
            }

        } catch (PDOException $e) {
            echo $e;
        }
    }

    private function checkArtistExistence($stmt): bool
    {
        try {
            $stmt->execute();
            if ($stmt->rowCount() > 0) {
                return true;
            }
            return false;
        } catch (PDOException $e) {
            $message = '[' . date("F j, Y, g:i a e O") . ']' . $e->getMessage() . $e->getCode() . $e->getFile() . ' Line ' . $e->getLine() . PHP_EOL;
            error_log("Database connection failed: " . $message, 3, __DIR__ . "/../Errors/error.log");
            http_response_code(500);
            exit();
        }
    }

    public function getArtistByIdWithUrl($id)
    {
        try {

            $stmt = $this->connection->prepare("SELECT * From artist WHERE artistId LIKE :id");
            $stmt->bindValue(':id', "%$id%");
            if ($this->checkArtistExistence($stmt)) {
                $stmt->execute();
                $result = $stmt->fetch();
                return $result;
            }

        } catch (PDOException $e) {
            echo $e;
        }
    }

    public function getAllStyles(): ?array
    {
        $styles = array();
        $query = "SELECT styleId,styleName FROM style";
        $result = $this->executeQuery($query);
        if (empty($result)) {
            return null; // null pointer exception
        }
        foreach ($result as $row) {
            $styles[] = new Style($row['styleId'], $row['styleName']);
        }
        return $styles;
    }

    /**
     * @throws DatabaseQueryException
     */
    public function addArtist($data): bool
    {
        $query = "INSERT INTO artist (artistName,artistDescription,artistLogoId) VALUES (:artistName,:artistDescription,:artistLogoId)";
        $artistID = $this->executeQuery($query, array(':artistName' => $data['artistName'], ':artistDescription' => $data['artistDescription'], ':artistLogoId' => $data['artistLogo']), false, true);
        if (!is_numeric($artistID)) {
            throw new DatabaseQueryException("Error while inserting artist");
        }
        foreach ($data['artistStyles'] as $style) {
            $this->addArtistStyle($artistID, $style);
        }
        foreach ($data['others'] as $key => $imageId) {
            $this->insertArtistImageWithArtistIdAndImageId($artistID, $imageId, 'Other');
        }
        $this->insertArtistImageWithArtistIdAndImageId($artistID, $data['portrait'], 'portrait');
        $this->insertArtistImageWithArtistIdAndImageId($artistID, $data['banner'], 'banner');

        return true; // if something wrong it will throw me an error and will not reach here
    }


    /**
     * @throws DatabaseQueryException
     */
    private function insertArtistImageWithArtistIdAndImageId($artistId, $imageId, $specification)
    {
        $query = "INSERT INTO artistImage (artistId,imageId,ImageSpecification) VALUES (:artistId,:imageId,:ImageSpecification)";
        if (!$this->executeQuery($query, array(':artistId' => $artistId, ':imageId' => $imageId, ':ImageSpecification' => $specification))) {
            // since it is an insert query so execute Query will return false if it was not inserted successFully
            throw new DatabaseQueryException("Error while inserting artist image");
        } // if it is false we have an error
    }

    /**
     * @throws DatabaseQueryException
     */
    public function addArtistStyle($artistId, $styleId)
    {
        $query = "INSERT INTO artistStyle (artistId,styleId) VALUES (:artistId,:styleId)";
        if (!$this->executeQuery($query, array(':artistId' => $artistId, ':styleId' => $styleId))) {
            // since it is an insert query so execute Query will return false if it was not inserted successFully
            throw new DatabaseQueryException("Error while inserting artist style");
        } // if it is false we have an error
    }

    public function artistExistenceInDatabase($artistName, $artistDescription): bool
    {
        $query = "SELECT artistId   FROM artist WHERE artistName = :artistName AND artistDescription = :artistDescription";
        $result = $this->executeQuery($query, array(':artistName' => $artistName, ':artistDescription' => $artistDescription)); // it is difficult to have same name and have same description of the artist
        if (empty($result)) {
            return false; // if there is no result then it means that the artist does not exist in the database
        }
        return true;
    }

    public function deleteArtist($artistId): bool
    {
        $query = "DELETE FROM artist WHERE artistId = :artistId";
        return $this->executeQuery($query, array(':artistId' => $artistId)); // it is going to return since it is a delete query
    }

    public function getAllImagesNameByArtistId($artistId): ?array
    {
        $query = "SELECT ImageName FROM (
                        SELECT LogoImage.ImageName AS ImageName FROM artist
                        JOIN image AS LogoImage ON artist.artistLogoId = LogoImage.imageId
                        WHERE artist.artistId = :artistId
                        UNION
                        SELECT image.ImageName AS ImageName FROM artist
                        JOIN artistImage ON artist.artistId = artistImage.artistId
                        JOIN image ON artistImage.imageId = image.imageId
                        WHERE artist.artistId = :artistId
                        ) AS ImageNames";
        $result = $this->executeQuery($query, array(':artistId' => $artistId));
        if (empty($result)) {
            return null;
        }
        $images = array();
        foreach ($result as $row) {
            $images[] = $row['ImageName'];
        }
        return $images;
    }

    public function isArtistAvailableAtTime($artistId, $date, $time): bool
    {
        $query = "SELECT performance.performanceId
                  FROM participatingartist
                  JOIN performance on participatingartist.performanceId = performance.performanceId
                  JOIN timetable on performance.timeTableId = timeTable.timeTableId
                  Join eventDate on timeTable.eventDateId = eventDate.eventDateId
                  WHERE participatingartist.artistId = :artistId AND eventDate.date = :date AND
                  :startTime <= DATE_ADD(timetable.time, INTERVAL performance.duration MINUTE)";
        $parameters = array(':artistId' => $artistId, ':date' => $date, ':startTime' => $time);
        $result = $this->executeQuery($query, $parameters);
        if (empty($result)) {
            return true;
        }
        return false;
    }

    public function isArtistParticipating($artistId): bool
    {
        $query = "SELECT artistId FROM participatingartist WHERE artistId = :artistId";
        $result = $this->executeQuery($query, [':artistId' => $artistId]);
        if (empty($result)) {
            return false;
        }
        return true;
    }

    public function getAllImagesIdOfArtist($artistId): ?array
    {
        $query = "SELECT ImageId FROM (
                        SELECT LogoImage.imageId AS ImageId FROM artist
                        JOIN image AS LogoImage ON artist.artistLogoId = LogoImage.imageId
                        WHERE artist.artistId = :artistId
                        UNION
                        SELECT image.imageId AS ImageId FROM artist
                                                        JOIN artistImage ON artist.artistId = artistImage.artistId
                                                        JOIN image ON artistImage.imageId = image.imageId
                        WHERE artist.artistId = :artistId
                    ) AS ImageIds";
        $result = $this->executeQuery($query, array(':artistId' => $artistId));
        if (empty($result)) {
            return null;
        }
        $images = array();
        foreach ($result as $row) {
            $images[] = $row['ImageId'];
        }
        return $images;
    }

    public function getAllStylesIdOfArtist($artistId): ?array
    {
        $query = "SELECT styleId FROM artistStyle WHERE artistId = :artistId";
        $result = $this->executeQuery($query, array(':artistId' => $artistId));
        if (empty($result)) {
            return null;
        }
        $styles = array();
        foreach ($result as $row) {
            $styles[] = $row['styleId'];
        }
        return $styles;
    }

    public function removeArtistStyle($artistId, $styleId): bool
    {
        $query = "DELETE FROM artistStyle WHERE artistId = :artistId AND styleId = :styleId";
        return $this->executeQuery($query, array(':artistId' => $artistId, ':styleId' => $styleId));
    }

    public function isArtistDetailsSameInDb($artistDetails): bool
    {
        $query = "SELECT artistId FROM artist WHERE artistName = :artistName AND artistDescription = :artistDescription";
        $result = $this->executeQuery($query, array(':artistName' => $artistDetails->name, ':artistDescription' => $artistDetails->description));
        if (empty($result)) {
            return false;
        }
        return true;
    }
    public function updateArtistDetails($artistDetails): bool
    {
        $query = "UPDATE artist SET artistName = :artistName, artistDescription = :artistDescription WHERE artistId = :artistId";
        return $this->executeQuery($query, array(':artistName' => $artistDetails->name,
            ':artistDescription' => $artistDetails->description,
            ':artistId' => $artistDetails->id));
    }

    public function getArtistLogoNameByArtistId($artistId)
    {
        $query ="SELECT image.ImageName FROM artist JOIN image ON artist.artistLogoId = image.imageId WHERE artist.artistId = :artistId";
        $result = $this->executeQuery($query, array(':artistId' => $artistId),false);
        if (empty($result)) {
            return null;
        }
        return $result['ImageName'];
    }
}
