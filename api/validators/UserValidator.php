<?php

function validateUserType($user_type) {
    // validate user type
    if ($user_type != UserType::USER->value &&
        $user_type != UserType::VENDOR->value &&
        $user_type != UserType::EVENT_ORGANIZER->value) {
        return "Invalid user type";
    }
    return null;
}

function validateUsername($username) {
    if (!preg_match('/^[a-zA-Z0-9_-]+$/', $username)) {
        return "Username can only contain letters, numbers, underscores and dashes";
    }

    if (strlen($username) < 3 || strlen($username) > 40) {
        return "Username must be between 3 and 40 characters";
    }

    return null;
}

function validateEmail($email) {
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return "Invalid email";
    }

    if (strlen($email) > 50) {
        return "Email must be shorter than 50 characters";
    }

    return null;
}

function validatePassword($password) {
    if (strlen($password) < 8) {
        return "Password must be longer than 8 characters";
    }

    return null;
}

function validateUserData($user_type, $username, $email, $password) {
    $errors = [];

    if ($user_type !== null) {
        $err = validateUserType($user_type);
        if ($err !== null) {
            $errors[] = $err;
        }
    }

    if ($username !== null) {
        $err = validateUsername($username);
        if ($err !== null) {
            $errors[] = $err;
        }
    }

    if ($email !== null) {
        $err = validateEmail($email);
        if ($err !== null) {
            $errors[] = $err;
        }
    }

    if ($password !== null) {
        $err = validatePassword($password);
        if ($err !== null) {
            $errors[] = $err;
        }
    }

    return $errors;
}