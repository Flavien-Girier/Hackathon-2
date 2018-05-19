<?php

namespace AppBundle\Controller;

use GuzzleHttp\Client;

class Characters
{
    const EMPIRE = 'Empire';
    const REPUBLIC = 'Republic';

    private $characters = [];

    public function __toString ()
    {
        return $this->characters['id'] . " - " . $this->characters['name'];
    }

    public function getAll() : array
    {
        $client = new Client([
            // Base URI is used with relative requests
            'base_uri' => 'https://akabab.github.io/starwars-api/api/',
            // You can set any number of default request options.
            'timeout'  => 2.0,
        ]);
        $response = $client->request('GET', 'all.json');
        $body = $response->getBody();
        $assoc = true;
        $characters = json_decode($body,$assoc);
        shuffle($characters);

        foreach ($characters as $key => $character) {
            if (!empty($characters[$key]['affiliations'])) {
                if (is_array($characters[$key]['affiliations'])) {
                    $characters[$key]['lastAffiliation'] = $characters[$key]['affiliations'][count($characters[$key]['affiliations'])-1];
                } else {
                    $characters[$key]['lastAffiliation'] = $characters[$key]['affiliations'];
                }
            }
        }
        
        foreach ($characters as $key => $character) {
            if (!empty($characters[$key]['homeworld'])) {
                if (is_array($characters[$key]['homeworld'])) {
                    $characters[$key]['lastHomeworld'] = strtolower($characters[$key]['homeworld'][count($characters[$key]['homeworld'])-1]);
                } else {
                    $characters[$key]['lastHomeworld'] = strtolower($characters[$key]['homeworld']);
                }
            }
        }
        
        return $characters;
    }

    public function getOneByID($id) : array
    {
        $client = new Client([
            // Base URI is used with relative requests
            'base_uri' => 'https://akabab.github.io/starwars-api/api/id/',
            // You can set any number of default request options.
            'timeout'  => 2.0,
        ]);
        $url = $id . '.json';
        $response = $client->request('GET', $url);
        $body = $response->getBody();
        $assoc = true;
        $this->characters = json_decode($body,$assoc);

        return $this->characters;
    }

    /**
     * return all possible characters for of the affiliations 'Republic' or 'Empire'
     *
     * @param array $characters
     * @param string $affiliation
     *
     * @return array
     */
    public function getExtractByAffiliation(array $characters, string $affiliation) : array
    {
        $newCharacters = [];
        if ($affiliation == $this::EMPIRE) {
            $ennemy = $this::REPUBLIC;
        } else {
            $ennemy = $this::EMPIRE;
        }
        foreach ($characters as $id => $character) {
            if (isset($character['affiliations'])) {
                for ($i = count($character['affiliations']) - 1; $i >= 0; $i--) {
                    if (strpos($character['affiliations'][$i],$ennemy) !== false) {
                        break;
                    }
                    if (strpos($character['affiliations'][$i],$affiliation) !== false) {
                        $newCharacters[] = $character;
                        break;
                    }
                }
            }
        }
        return $newCharacters;
    }

    /**
     * return all possible characters that have a specific string value
     *      for a specified first level parameter
     *
     * @param array $characters
     * @param string $parameter
     * @param string $value
     *
     * @return array
     */
    public function getExtractByParameterAndExactValue(array $characters, string $parameter, string $value) : array
    {
        $newCharacters = [];
        foreach ($characters as $id => $character) {
            if (isset($character[$parameter])) {
                if (is_array($character[$parameter])) {
                    for ($i = count($character[$parameter]) - 1; $i >= 0; $i--) {
                        if ($character[$parameter][$i] == $value) {
                            $newCharacters[] = $character;
                        }
                    }
                } else {
                    if ($character[$parameter] == $value) {
                        $newCharacters[] = $character;
                    }
                }
            }
        }
        return $newCharacters;
    }

    /**
     * return all possible characters that have a specific string value
     *      for a specified first level parameter
     *
     * @param array $characters
     * @param string $parameter
     * @param string $value
     *
     * @return array
     */
    public function getExtractByParameterAndValue(array $characters, string $parameter, string $value) : array
    {
        $newCharacters = [];
        foreach ($characters as $id => $character) {
            if (isset($character[$parameter])) {
                if (is_array($character[$parameter])) {
                    for ($i = count($character[$parameter]) - 1; $i >= 0; $i--) {
                        if (strpos($character[$parameter][$i], $value) !== false) {
                            $newCharacters[] = $character;
                        }
                    }
                } else {
                    if (strpos($character[$parameter], $value) !== false) {
                        $newCharacters[] = $character;
                    }
                }
            }
        }
        return $newCharacters;
    }

    /**
     * return all possible characters that have a specific string value
     *      for a specified first level paramater
     *
     * @param array $characters
     * @param string $parameter
     * @param string $value
     *
     * @return array
     */
    public function getExtractByParameterAndInverseOfValue(array $characters, string $parameter, string $value) : array
    {
        $newCharacters = [];
        foreach ($characters as $id => $character) {
            if (isset($character[$parameter])) {
                if (is_array($character[$parameter])) {
                    for ($i = count($character[$parameter]) - 1; $i >= 0; $i--) {
                        if (strpos($character[$parameter][$i], $value) === false) {
                            $newCharacters[] = $character;
                        }
                    }
                } else {
                    if (strpos($character[$parameter], $value) === false) {
                        $newCharacters[] = $character;
                    }
                }
            }
        }
        return $newCharacters;
    }

    /**
     * add more characters to the existing array if they are not duplicates
     *
     * @param array $characters
     * @param array $moreCharacters
     *
     * @return array
     */
    public function setExtractByDuplicates(array $characters, array $moreCharacters) : array {
        $ids = [];
        if (count($characters) !== 0) {
            foreach ($characters as $character) {
                $ids[] = $character['id'];
            }
        }

        $characters = [];

        if (count($moreCharacters) > 1) {
            foreach ($moreCharacters as $moreCharacter) {
                if (!in_array($moreCharacter['id'],$ids) || $ids == []) {
                    $characters[] = $moreCharacter;
                }
            }
        } elseif (count($moreCharacters) == 1) {
            if (!in_array($moreCharacters['id'],$ids) || $ids == []) {
                $characters[] = $moreCharacters;
            }
        }

        return $characters;
    }

    /**
     * return all possible characters that have a specific string value
     *      for a specified first level paramater
     *
     * @param array $characters
     * @param string $parameter
     *
     * @return array
     */
    public function getValuesByParameter(array $characters, string $parameter) : array
    {
        $values = [];
        foreach ($characters as $id => $character) {
            if (isset($character[$parameter])) {
                if (is_array($character[$parameter])) {
                    for ($i = count($character[$parameter]) - 1; $i >= 0; $i--) {
                        if (!in_array($character[$parameter][$i],$values)) {
                            $values[] = $character[$parameter][$i];
                        }
                    }
                } else {
                    if (!in_array($character[$parameter],$values)) {
                        $values[] = $character[$parameter];
                    }
                }
            }
        }
        sort($values);
        return $values;

    }

    /**
     * select characters with a very different height
     *
     * @param array $characters
     * @param float $height
     *
     * @return array
     */
    public function getAllDifferentByHeight(array $characters, float $height) : array
    {
        $newCharacters = [];
        foreach ($characters as $character) {
            if (!empty($character['height']) && ( ($height / $character['height']) > 1.11 || ($height / $character['height']) < 0.9 )) {
                $newCharacters[] = $character;
            }
        }
        return $newCharacters;
    }

    /**
     * select characters with a very different mass
     *
     * @param array $characters
     * @param float $mass
     *
     * @return array
     */
    public function getAllDifferentByMass(array $characters, float $mass) : array
    {
        $newCharacters = [];
        foreach ($characters as $character) {
            if (!empty($character['mass']) && ( ($mass / $character['mass']) > 3 || ($mass / $character['mass']) < 0.33 )) {
                $newCharacters[] = $character;
            }
        }
        return $newCharacters;
    }

}
