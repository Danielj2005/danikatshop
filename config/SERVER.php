<?php

/*----------  Datos del servidor  ----------*/
const SERVER="localhost";
const DB="danikat_db";
const USER="root";
const PASS="";

const SGBD="mysql:host=".SERVER.";dbname=".DB;

/*
const HOST = 'sql204.infinityfree.com'; 
const DB_NAME = 'if0_41737603_danikat_bd';
const USERNAME = 'if0_41737603';
const PASSWORD = '0iAMk3Kc0lb'; 
*/

const HOST = 'localhost'; 
const DB_NAME = 'danikat_db';
const USERNAME = 'root';
const PASSWORD = '';

/*----------  Datos de la encriptacion (No modificar) ----------*/
const METHOD="AES-256-CBC";
const SECRET_KEY='';
const SECRET_IV='102791';

$conn = new PDO("mysql:host=".HOST.";dbname=".DB_NAME, USERNAME, PASSWORD);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
