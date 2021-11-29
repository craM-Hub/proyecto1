<?php

interface IPasswordGenerator
{
    public static function encrypt(string $plainPassword): string;
    public static function passwordVerify($password, $hash): bool;
}
