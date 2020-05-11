<?
global $USER;

if (!$USER->IsAuthorized()) {
    LocalRedirect('/');
}