<?php
require_once __DIR__ . '/../Vendor/autoload.php';
class SpotifyService
{
    private $spotifyAPI;
    private $cilentCountryCode; // required to search the artist top tracks and albums so that we can get the results according to result country client country

    public function __construct()
    {
        $this->spotifyAPI = $this->createSpotifySession();
        $this->cilentCountryCode = 'NL';
//        if(empty($this->cilentCountryCode)){  //TODO :  After Deployment
//            $this->cilentCountryCode = 'NL';
//        }
    }
    private function createSpotifySession()
    {
        require_once __DIR__ . '/../config/RESTAPIsConfig/SpotifyConfig.php'; // getting the config file
        try {
            $session = new SpotifyWebAPI\Session(
                $clientId,
                $clientSecret
            );
            $session->requestCredentialsToken();
           $accessToken= $session->getAccessToken();
            $api = new SpotifyWebAPI\SpotifyWebAPI();
            $api->setAccessToken($accessToken);
            return $api;
        } catch (\SpotifyWebAPI\SpotifyWebAPIException | \SpotifyWebAPI\SpotifyWebAPIAuthException  $e) {
            //echo $e->getMessage();
        }

    }

    /**
     * @throws \SpotifyWebAPI\SpotifyWebAPIAuthException
     */
    private function getArtistIDByArtistName($artistName){
        if(empty($this->spotifyAPI)){
            throw  new \SpotifyWebAPI\SpotifyWebAPIAuthException("Unable to connect with spotify");
        }
        // Get the first artist from the results
        $results = $this->spotifyAPI->search($artistName, 'artist');
        // Get the first artist from the results
        $artist = $results->artists->items[0];

        return $artist->id;
    }

    /**
     * @throws \SpotifyWebAPI\SpotifyWebAPIAuthException
     */
    public  function getArtistTopTracksWithLimit($artistName, $limit): object|array
    {
        if (!empty($this->spotifyAPI)) {

            $topTracks = $this->spotifyAPI->getArtistTopTracks($this->getArtistIDByArtistName($artistName), [
                'market' => $this->cilentCountryCode
            ]);

            // returning desired track limit
            return array_slice($topTracks->tracks, 0, $limit);

        } else {
            throw  new \SpotifyWebAPI\SpotifyWebAPIAuthException("Unable to connect with spotify");
        }
    }

    /**
     * @throws \SpotifyWebAPI\SpotifyWebAPIAuthException
     */
    public function getArtistAlbumsWithLimit($artistName, $limit): object|array
    {
        if (!empty($this->spotifyAPI)) {
            // Get the artist's albums
            return $this->spotifyAPI->getArtistAlbums($this->getArtistIDByArtistName($artistName), [
                'limit' => $limit,
                'market' => $this->cilentCountryCode

            ]);
        } else {
            throw  new \SpotifyWebAPI\SpotifyWebAPIAuthException("Unable to connect with spotify");
        }
    }
    private function getClientCountryCode() :string{
        // Get client's IP address
        $clientIP = $_SERVER['REMOTE_ADDR'];
        // Initiate curl and pass your API URL
        $ch = curl_init("https://ipapi.co/{$clientIP}/country_code");
        // Set curl options
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        // Execute curl and store received data
        $countryCode = curl_exec($ch);

        // Close curl
        curl_close($ch);

        // returning the country code
        return $countryCode;
    }
}