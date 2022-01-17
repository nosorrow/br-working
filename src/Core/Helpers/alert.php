<?php

if (!function_exists('alert')) {

    /**
     * success
     * info
     * warning
     * danger
     * @param $type
     * @return int
     */
    function alert($type, $msg)
    {
        switch ($type) {
            case 'success':
                $alert = '<div class="alert alert-success" role="alert">' . $msg . '</div>';
                break;
            case 'info':
                $alert = '<div class="alert alert-info" role="alert">' . $msg . '</div>';
                break;
            case 'warning':
                $alert = '<div class="alert alert-warning" role="alert">' . $msg . '</div>';
                break;
            case 'danger':
                $alert = '<div class="alert alert-danger" role="alert">' . $msg . '</div>';
                break;

        }

        return $alert;
    }
}

