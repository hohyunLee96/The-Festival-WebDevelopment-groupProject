<?php
require_once __DIR__ . '/../services/NavBarService.php';
require_once __DIR__ . '/../models/ImageManager.php';

abstract class Controller
{
    private NavBarService $navBarService;
    use ImageManager;

    public function __construct()
    {
        $this->navBarService = new NavBarService();
    }

    function displayView($model)
    {
        $directory = strtolower(substr(get_class($this), 0, -10));
        $view = debug_backtrace()[1]['function'];
        require __DIR__ . "/../views/$directory/$view.php";
    }

    // since festival files are inside a folder, the path is different.
    function displayViewFestival($model)
    {
        $directory = strtolower(substr(get_class($this), 0, -10));
        $view = debug_backtrace()[1]['function'];
        require __DIR__ . "/../views/festival/$directory/$view.php";
    }

    function displayPageView($view)
    {
        $directory = strtolower(substr(get_class($this), 0, -10));
        require __DIR__ . "/../views/$directory/$view.php";
    }

    // since festival files are inside a folder, the path is different.
    function displayPageViewFestival($view)
    {
        $directory = strtolower(substr(get_class($this), 0, -10));
        require __DIR__ . "/../views/festival/$directory/$view.php";
    }

    protected function parseDateOfBirth($date): bool|string
    {
        $current_date = new DateTime();
        $birthDate = DateTime::createFromFormat('Y-m-d', $date);
        if ($birthDate === false || array_sum($birthDate->getLastErrors()) > 0) {
            return "please input a valid date format (YYYY-MM-DD) for birthdate";
        }
        if ($date > $current_date->format('Y-m-d')) {
            return "Please select a date that is not in the future";
        }
        return true;
    }

    protected function displayNavBar($title, $pathToCss = null): void
    {
        $this->navBarService = new NavBarService();
        $navBarItems = $this->navBarService->getAllNavBarItems();
        $festivalNavBarItems = $this->festivalNavBarItems($navBarItems);
        require_once __DIR__ . '/../views/HomeNavBar.php';
    }

    private function festivalNavBarItems($navaBarItems)
    {
        $festivalNavBarItems = [];
        foreach ($navaBarItems as $navBarItem) {
            if (strcasecmp((explode('/',$navBarItem->getNavBarUrl())[1]), "Festival") == 0) {
                $festivalNavBarItems[] = $navBarItem;
            }
        }
        return $festivalNavBarItems;
    }

    function displayViewUsingPermissions($model, $user)
    {
        $directory = strtolower(substr(get_class($this), 0, -10));
        $view = debug_backtrace()[1]['function'];
        $currentUserId = $user;
        $pageId = func_get_arg(2);
        require __DIR__ . "/../views/$directory/$view.php";
    }

    protected function sanitizeInput($input)
    {
        return htmlspecialchars($input);
    }

    protected function logoutUser(): void
    {
        unset($_SESSION["loggedUser"]);
        header("location: /login");
        die();
    }

    protected function display404PageNotFound(): void
    {
        require_once __DIR__ . '/../views/PageNotFound.html';
    }

    protected function displayFooter(): void
    {
        require_once __DIR__ . '/../views/Footer.php';
    }

    protected function checkFieldsFilledAndSantizeInput(array $inputArray, array $excludedKeys = [], array $arraysInputField = []): array|string
    {
        $sanitizedInputArray = [];
        if (!empty($arraysInputField)) {
            $missingFields = $this->getMissingFields($inputArray, $arraysInputField);

            if (!empty($missingFields)) {
                $missingFields = implode(", ", $missingFields);
                return "Please fill the following fields: " . $missingFields;
            }
        }
        foreach ($inputArray as $key => $value) {
            if (empty($value) && !in_array($key, $excludedKeys)) {
                return "Please enter a value for the field " . $key;
            }
            $sanitizedInputArray[$key] = $value;
            if (is_array($value)) {
                $newValue = [];
                foreach ($value as $subKey => $subValue) {
                    $newValue[$subKey] = $this->sanitizeInput($subValue);
                }
                $sanitizedInputArray[$key] = $newValue;
            } else {
                $sanitizedInputArray[$key] = $this->sanitizeInput($value);
            }
        }
        return $sanitizedInputArray;
    }

    private function getMissingFields(array $inputArray, array $checkBoxKeys): array
    {
        $missingCheckBoxFields = [];
        foreach ($checkBoxKeys as $checkBoxKey) {
            if (!array_key_exists($checkBoxKey, $inputArray)) {
                $missingCheckBoxFields[] = $checkBoxKey;
            }
        }
        return $missingCheckBoxFields;
    }

    protected function processImagesWithFiles($images, array $multipleImages = []): array|string
    {
        $processedImages = [];
        foreach ($images as $key => $image) {
            if ($image['error'] == UPLOAD_ERR_NO_FILE) {
                return "please upload an Image for  : " . $key;
            }
            if (in_array($key, $multipleImages)) {
                if ($image['error'][0] == UPLOAD_ERR_NO_FILE) { // atLeast one picture needs to be uploaded
                    return "Please upload images for : " . $key;
                }
                foreach ($image['tmp_name'] as $subKey => $tmpName) {
                    $eachImage = [
                        'name' => $image['name'][$subKey],
                        'type' => $image['type'][$subKey],
                        'tmp_name' => $tmpName,
                        'error' => $image['error'][$subKey],
                        'size' => $image['size'][$subKey]
                    ];
                    if ($this->checkValidImageOrNot($eachImage)) {
                        $processedImages[$key][] = $eachImage;
                    } else {
                        return "Please upload a valid image for : " . $key;
                    }
                }
            } else {
                if ($this->checkValidImageOrNot($image)) {
                    $processedImages[$key] = $image;
                } else {
                    return "Please upload a valid image for : " . $key;
                }
            }
        }
        return $processedImages;
    }
}

