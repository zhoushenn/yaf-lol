<?php
namespace Service\user;

interface UserInterface{

    public function valid($username, $password);

    public function validByToken($token);

    public function getUserByToken($token);

    public function generateToken($name);

    public function setRemberMeToken($userId, $token, $expire);

    public function delRemberMeToken();

}
