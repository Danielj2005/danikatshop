<?php

/*----------  Datos del servidor  ----------*/
const SERVER="localhost";
const DB="danikat_db";
const USER="root";
const PASS="";

const SGBD="mysql:host=".SERVER.";dbname=".DB;

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
