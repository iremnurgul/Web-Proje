<?php

function replaceInFile($file, $search, $replace) {
    if (!file_exists($file)) {
        echo "File not found: $file\n";
        return false;
    }
    $content = file_get_contents($file);
    if (strpos($content, $search) !== false) {
        $content = str_replace($search, $replace, $content);
        file_put_contents($file, $content);
        echo "Updated $file\n";
        return true;
    } else {
        echo "Search string not found in $file\n";
        return false;
    }
}

// 1. Update login.php
$loginFile = __DIR__ . '/views/auth/login.php';
$searchLogin = '<input type="number" name="user_number" class="form-control <?php echo (!empty($data[\'user_number_err\'])) ? \'is-invalid\' : \'\'; ?>" value="<?php echo isset($data[\'user_number\']) ? $data[\'user_number\'] : \'\'; ?>" autofocus>';
$replaceLogin = '<input type="number" inputmode="numeric" pattern="[0-9]*" name="user_number" class="form-control <?php echo (!empty($data[\'user_number_err\'])) ? \'is-invalid\' : \'\'; ?>" value="<?php echo isset($data[\'user_number\']) ? $data[\'user_number\'] : \'\'; ?>" placeholder="Örn: 1001" autofocus>';
replaceInFile($loginFile, $searchLogin, $replaceLogin);

// 2. Update register.php
$regFile = __DIR__ . '/views/auth/register.php';
$searchReg = '<input type="number" name="user_number" class="form-control <?php echo (!empty($data[\'user_number_err\'])) ? \'is-invalid\' : \'\'; ?>" value="<?php echo isset($data[\'user_number\']) ? $data[\'user_number\'] : \'\'; ?>">';
$replaceReg = '<input type="number" inputmode="numeric" pattern="[0-9]*" name="user_number" class="form-control <?php echo (!empty($data[\'user_number_err\'])) ? \'is-invalid\' : \'\'; ?>" value="<?php echo isset($data[\'user_number\']) ? $data[\'user_number\'] : \'\'; ?>" placeholder="Örn: 1001">';
replaceInFile($regFile, $searchReg, $replaceReg);

echo "Done.\n";
